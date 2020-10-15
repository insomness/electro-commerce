<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Jobs\SendEmailOrderReceived;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::forUser(Auth::user())
            ->orderBy('created_at', 'DESC')
            ->paginate(6);

        $this->data['orders'] = $orders;

        return $this->loadTheme('orders.index', $this->data);
    }

    public function show($id)
    {
        $order = Order::forUser(Auth::user())->findOrFail($id);
        $this->data['order'] = $order;

        return $this->loadTheme('orders.show', $this->data);
    }

    public function checkout()
    {
        if (\Cart::isempty()) {
            return redirect('carts');
        }

        $this->_updateTax();

        $provinces = $this->getProvinces();
        array_unshift($provinces, 'Select Province');

        $this->data['carts'] = \Cart::getContent();
        $this->data['totalWeight'] = $this->_getTotalWeight() / 1000;
        $this->data['cities'] = isset(\Auth::user()->province_id) ? $this->getCities(\Auth::user()->province_id) : [];
        $this->data['provinces'] = $provinces;
        $this->data['user'] = Auth::user();

        return $this->loadTheme('orders.checkout', $this->data);
    }

    private function _updateTax()
    {
        \Cart::removeConditionsByType('tax');

        $condition = new \Darryldecode\Cart\CartCondition(
            [
                'name' => 'TAX 10%',
                'type' => 'tax',
                'target' => 'total',
                'value' => '10%',
            ]
        );

        \Cart::condition($condition);
    }

    public function doCheckout(OrderRequest $request)
    {
        $params = $request->except('_token');

        $order = DB::transaction(function () use ($params) {
            $order = $this->_saveOrder($params);
            $this->_saveOrderItems($order);
            $this->_generatePaymentToken($order);
            $this->_saveShipment($order, $params);

            return $order;
        });

        if ($order) {
            // \Cart::clear();
            $this->_sendEmailOrderReceived($order);

            Session::flash('success', 'Thank you. Your order has been received!');
            return redirect('orders/received/' . $order->id);
        }

        return redirect('orders/checkout');
    }

    /**
     * Generate payment token
     *
     * @param Order $order order data
     *
     * @return void
     */
    private function _generatePaymentToken($order)
    {
        $this->initPaymentGateway();

        $customerDetails = [
            'first_name' => $order->customer_first_name,
            'last_name' => $order->customer_last_name,
            'email' => $order->customer_email,
            'phone' => $order->customer_phone,
        ];

        $params = [
            'enable_payments' => Payment::PAYMENT_CHANNELS,
            'transaction_details' => [
                'order_id' => $order->code,
                'gross_amount' => $order->grand_total,
            ],
            'customer_details' => $customerDetails,
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s T'),
                'unit' => Payment::EXPIRY_UNIT,
                'duration' => Payment::EXPIRY_DURATION,
            ],
        ];

        $snap = \Midtrans\Snap::createTransaction($params);

        if ($snap->token) {
            $order->payment_token = $snap->token;
            $order->payment_url = $snap->redirect_url;
            $order->save();
        }
    }

    private function _sendEmailOrderReceived($order)
    {
        dispatch(new SendEmailOrderReceived($order, Auth::user()));
    }

    protected function _saveOrder($params)
    {
        $destination = isset($params['ship_to']) ?  $params['shipping_city'] : $params['city_id'];
        $selectedShipping = $this->_getSelectedShipping($destination, $this->_getTotalWeight(), $params['shipping_service']);

        $baseTotalPrice = \Cart::getSubTotal();
        $taxAmount = \Cart::getCondition('TAX 10%')->getCalculatedValue(\Cart::getSubTotal());
        $taxPercent = (float) \Cart::getCondition('TAX 10%')->getValue();

        $shippingCost = $selectedShipping['costAmount'];
        $grandTotal = ($baseTotalPrice + $shippingCost + $taxAmount);

        $orderDate = date('Y-m-d H:i:s');
        $paymentDue = (new \DateTime($orderDate))->modify('+7 day')->format('Y-m-d H:i:s');

        $orderParams = [
            'user_id' => Auth::user()->id,
            'code' => Order::generateOrderCode(),
            'status' => Order::CREATED,
            'order_date' => $orderDate,
            'payment_due' => $paymentDue,
            'payment_status' => Order::UNPAID,
            'base_total_price' => $baseTotalPrice,
            'tax_amount' => $taxAmount,
            'tax_percent' => $taxPercent,
            'shipping_cost' => $shippingCost,
            'grand_total' => $grandTotal,
            'note' => $params['note'],
            'customer_first_name' => $params['first_name'],
            'customer_last_name' => $params['last_name'],
            'customer_address' => $params['address'],
            'customer_phone' => $params['phone'],
            'customer_email' => $params['email'],
            'customer_city_id' => $params['city_id'],
            'customer_province_id' => $params['province_id'],
            'customer_postcode' => $params['postcode'],
            'shipping_courier' => $selectedShipping['courier'],
            'shipping_service_name' => $selectedShipping['services'],
        ];

        return Order::create($orderParams);
    }

    /**
     * Save order items
     *
     * @param Order $order order object
     *
     * @return void
     */
    private function _saveOrderItems($order)
    {
        $cartItems = \Cart::getContent();

        if ($order && $cartItems) {
            foreach ($cartItems as $item) {
                $itemTaxAmount = 0;
                $itemTaxPercent = 0;
                $itemBaseTotal = $item->quantity * $item->price;
                $itemSubTotal = $itemBaseTotal + $itemTaxAmount;

                $orderItemParams = [
                    'order_id' => $order->id,
                    'product_id' => $item->associatedModel->id,
                    'qty' => $item->quantity,
                    'base_price' => $item->price,
                    'base_total' => $itemBaseTotal,
                    'tax_amount' => $itemTaxAmount,
                    'tax_percent' => $itemTaxPercent,
                    'sub_total' => $itemSubTotal,
                    'sku' => $item->associatedModel->sku,
                    'name' => $item->name,
                    'weight' => $item->associatedModel->weight,
                ];

                $orderItem = OrderItem::create($orderItemParams);
            }
        }
    }

    /**
     * Save shipment data
     *
     * @param Order $order  order object
     * @param array $params checkout params
     *
     * @return void
     */
    private function _saveShipment($order, $params)
    {
        $shippingFirstName = isset($params['ship_to']) ? $params['shipping_first_name'] : $params['first_name'];
        $shippingLastName = isset($params['ship_to']) ? $params['shipping_last_name'] : $params['last_name'];
        $shippingAddress = isset($params['ship_to']) ? $params['shipping_address'] : $params['address'];
        $shippingPhone = isset($params['ship_to']) ? $params['shipping_phone'] : $params['phone'];
        $shippingEmail = isset($params['ship_to']) ? $params['shipping_email'] : $params['email'];
        $shippingCityId = isset($params['ship_to']) ? $params['shipping_city_id'] : $params['city_id'];
        $shippingProvinceId = isset($params['ship_to']) ? $params['shipping_province_id'] : $params['province_id'];
        $shippingPostcode = isset($params['ship_to']) ? $params['shipping_postcode'] : $params['postcode'];

        $shipmentParams = [
            'user_id' => Auth::user()->id,
            'order_id' => $order->id,
            'status' => Shipment::PENDING,
            'total_qty' => \Cart::getTotalQuantity(),
            'total_weight' => $this->_getTotalWeight(),
            'first_name' => $shippingFirstName,
            'last_name' => $shippingLastName,
            'address' => $shippingAddress,
            'phone' => $shippingPhone,
            'email' => $shippingEmail,
            'city_id' => $shippingCityId,
            'province_id' => $shippingProvinceId,
            'postcode' => $shippingPostcode,
        ];

        Shipment::create($shipmentParams);
    }

    protected function _getSelectedShipping($destination, $weight, $shippingService)
    {
        $shippingOptions = $this->_getshippingCost($destination, $weight);
        foreach ($shippingOptions['results'] as $shippingOption) {
            if (str_replace(' ', '', $shippingOption['services']) == $shippingService) {
                $selectedShipping = $shippingOption;
                break;
            }
        }

        return $selectedShipping;
    }

    protected function _getTotalWeight()
    {
        if (\Cart::isEmpty()) {
            return 0;
        }

        $carts = \Cart::getContent();
        $totalWeight = 0;
        foreach ($carts as $cart) {
            $totalWeight += ($cart->associatedModel->weight * $cart->quantity);
        }

        return $totalWeight;
    }

    public function shippingCost(Request $request)
    {
        $destination = $request->city_id;
        return $this->_getshippingCost($destination, $this->_getTotalWeight());
    }

    private function _getshippingCost($destination, $weight)
    {
        $params = [
            'weight' => $weight,
            'destination' => $destination,
            'origin' => config('app.rajaongkir_origin'),
        ];

        $results = [];
        foreach ($this->couriers as $code => $courier) {
            $params['courier'] = $code;
            $response = $this->rajaOngkirRequest('cost', 'post', $params);

            if (!empty($response['rajaongkir']['results'])) {
                foreach ($response['rajaongkir']['results'] as $result) {
                    if (!empty($result['costs'])) {
                        foreach ($result['costs'] as $costDetail) {
                            $services = strtoupper($result['code']) . '-' . $costDetail['service'];
                            $etd = $costDetail['cost'][0]['etd'];
                            $costAmount = $costDetail['cost'][0]['value'];

                            $results[] = [
                                'services' => $services,
                                'etd' => $etd,
                                'costAmount' => $costAmount,
                                'courier' => $code
                            ];
                        }
                    }
                }
            }
        }

        $response = [
            'origin' => $params['origin'],
            'weight' => $params['weight'],
            'destination' => $params['destination'],
            'results' => $results,
        ];

        return $response;
    }

    public function setShipping(Request $request)
    {
        \Cart::removeConditionsByType('shipping');

        $destination = $request->city_id;
        $shippingService = $request->shippingService;

        $selectedShipping = $this->_getSelectedShipping($destination, $this->_getTotalWeight(), $shippingService);

        $data = [];
        if ($selectedShipping) {
            $status = 200;
            $message = "success set shipping cost";

            $this->_addCartConditions($selectedShipping['services'], $selectedShipping['costAmount']);

            $data['total'] = formatRupiah(\Cart::getTotal());
        } else {
            $status = 400;
            $message = "Failed to set shipping cost";
        }

        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return $response;
    }

    private function _addCartConditions($selectedShipping, $cost)
    {
        $condition = new \Darryldecode\Cart\CartCondition([
            'name' => $selectedShipping,
            'type' => 'shipping',
            'target' => 'total',
            'value' => '+' . $cost,
        ]);

        \Cart::condition($condition);
    }

    public function getCityList(Request $request)
    {
        $response = $this->getCities($request->query('province_id'));
        return response()->json($response, 200);
    }

    /**
     * Show the received page for success checkout
     *
     * @param int $orderId order id
     *
     * @return void
     */
    public function received($orderId)
    {
        $this->data['order'] = Order::where('id', $orderId)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return $this->loadTheme('orders/received', $this->data);
    }
}

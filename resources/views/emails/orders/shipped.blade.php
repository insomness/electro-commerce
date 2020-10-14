@component('mail::message')
# Yeay, your order has been shipped!
We shipped your order at <strong>{{dateTimeFormat($order->shipment->shipped_at)}}</strong> and here is your tracking number: <strong>{{ $order->shipment->track_number }}</strong>
<br>
<br/>
Your order details are shown below for your reference:
## Order #{{ $order->code }} ({{dateTimeFormat($order->order_date)}})

@component('mail::table')
| Product       | Quantity      | Price  |
| ------------- |:-------------:| --------:|
@foreach ($order->products as $item)
| {{ $item->name }}      |  {{ $item->pivot->qty }}      | {{ formatRupiah($item->pivot->sub_total) }}      |
@endforeach
| &nbsp;         | <strong>Sub total</strong> | {{ formatRupiah($order->base_total_price) }} |
| &nbsp;         | Tax (10%)     | {{ formatRupiah($order->tax_amount) }} |
| &nbsp;         | Shipping cost | {{ formatRupiah($order->shipping_cost) }} |
| &nbsp;         | <strong>Total</strong> | <strong>{{ formatRupiah($order->grand_total) }}</strong>|
@endcomponent

## Billing Details:
<strong>{{ $order->customer_first_name }} {{ $order->customer_last_name }}</strong>
<br> {{ $order->customer_address }}
<br> Email: {{ $order->customer_email }}
<br> Phone: {{ $order->customer_phone }}
<br> Postcode: {{ $order->customer_postcode }}

## Shipment Address (shipped by: {{ $order->shipping_service_name }}):
<strong>{{ $order->shipment->first_name }} {{ $order->shipment->last_name }}</strong>
<br> {{ $order->shipment->address }}
<br> Email: {{ $order->shipment->email }}
<br> Phone: {{ $order->shipment->phone }}
<br> Postcode: {{ $order->shipment->postcode }}

@component('mail::button', ['url' => url('orders/received/'. $order->id)])
Show order detail
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

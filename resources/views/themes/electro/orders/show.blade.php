@extends('themes.electro.app')
@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                @include('themes.electro.partials.flash')
                <div class="col-md-3">
                    @include('themes.electro.partials.user_menu')
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <h3 class="title">Your Order</h3>
                        <div class="col-md-4">
                            <h5>Billing Address</h5>
                            <address>
                                {{ $order->customer_first_name }} {{ $order->customer_last_name }}
                                <br> {{ $order->customer_address }}
                                <br> Email: {{ $order->customer_email }}
                                <br> Phone: {{ $order->customer_phone }}
                                <br> Postcode: {{ $order->customer_postcode }}
                            </address>
                        </div>
                        <div class="col-md-4">
                            <h5>Shipment Address</h5>
                            <address>
                                {{ $order->shipment->first_name }} {{ $order->shipment->last_name }}
                                <br> {{ $order->shipment->address }}
                                <br> Email: {{ $order->shipment->email }}
                                <br> Phone: {{ $order->shipment->phone }}
                                <br> Postcode: {{ $order->shipment->postcode }}
                            </address>
                        </div>
                        <div class="col-md-4">
                            <h5>Details</h5>
                            <address>
                                Invoice ID:
                                <span class="text-dark">#{{ $order->code }}</span>
                                <br> {{ datetimeFormat($order->order_date) }}
                                <br> Status: {{ $order->status }}
                                <br> Payment Status: {{ $order->payment_status }}
                                <br> Shipped by: {{ $order->shipping_service_name }}
                            </address>
                        </div>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->products as $item)
                                <tr style="height:50px;">
                                    <td>{{ $item->pivot->sku }}</td>
                                    <td>{{ $item->pivot->name }}</td>
                                    <td>{{ $item->pivot->qty }}</td>
                                    <td>{{ formatRupiah($item->pivot->base_price) }}</td>
                                    <td>{{ formatRupiah($item->pivot->sub_total) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Order item not found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-5 order-details pull-right">
                            <div class="order-summary">
                                <div class="order-col">
                                    <div><strong>SubTotal</strong></div>
                                    <div>{{formatRupiah($order->base_total_price)}}</div>
                                </div>
                                <div class="order-col">
                                    <div><strong>Tax</strong></div>
                                    <div>{{formatRupiah($order->tax_amount)}}</div>
                                </div>
                                <div class="order-col">
                                    <div><strong>Shipping Cost</strong></div>
                                    <div>{{formatRupiah($order->shipping_cost)}}</div>
                                </div>
                                <div class="order-col">
                                    <div><strong>TOTAL</strong></div>
                                    <div>{{formatRupiah($order->grand_total)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="{{asset('themes/electro/js/checkout.js')}}"></script>
@endpush

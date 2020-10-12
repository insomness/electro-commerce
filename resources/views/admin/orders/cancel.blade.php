@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Cancel Order {{$order->code}}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cancelled Note</h3>
            </div>
            {!! Form::open(['route' => ['admin.orders.do_cancel', $order->id], 'method' => 'PUT']) !!}
            <div class="card-body">
                {!! Form::textarea('cancellation_note', null, ['class' => 'form-control']) !!}
            </div>
            <div class="card-footer">
                {!! Form::submit('Cancel Order', ['class' => 'btn btn-block btn-success btn-flat']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                <h4>
                    Order Details
                    <small class="float-right">Date: {{$order->created_at->format('d/m/Y')}}</small>
                </h4>
                </div>
                <!-- /.col -->
            </div>

            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                Billing Address
                <address>
                    <address>
                        {{ $order->customer_first_name }} {{ $order->customer_last_name }}
                        <br> {{ $order->customer_address }}
                        <br> Email: {{ $order->customer_email }}
                        <br> Phone: {{ $order->customer_phone }}
                        <br> Postcode: {{ $order->customer_postcode }}
                    </address>
                </div>
                <div class="col-sm-6 invoice-col">
                Details
                <address>
                    Invoice ID:
                    <span class="text-dark">#{{ $order->code }}</span>
                    <br> {{ datetimeFormat($order->order_date) }}
                    <br> Status: {{ $order->status }}
                    <br> Payment Status: {{ $order->payment_status }}
                    <br> Shipped by: {{ $order->shipping_service_name }}
                </address>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Qty</th>
                            <th>Product</th>
                            <th>Serial #</th>
                            <th>Description</th>
                            <th>Subtotal</th>
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
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width:50%">Subtotal:</th>
                                    <td>{{formatRupiah($order->base_total_price)}}</td>
                                </tr>
                                <tr>
                                    <th>Tax (10%)</th>
                                    <td>{{formatRupiah($order->tax_amount)}}</td>
                                </tr>
                                <tr>
                                    <th>Shipping:</th>
                                    <td>{{formatRupiah($order->shipping_cost)}}</td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td>{{formatRupiah($order->grand_total)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@stop

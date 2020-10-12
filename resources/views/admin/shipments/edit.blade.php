@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Order Shipment {{$shipment->order->code}}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Order Shipment</h3>
            </div>
            {!! Form::model($shipment,['route' => ['admin.shipments.update', $shipment->id], 'method' => 'PUT']) !!}
            <div class="card-body">
                <div class="form-group form-row">
                    <div class="col">
                        {!! Form::label('first_name', 'First Name', []) !!}
                        {!! Form::text('first_name', null, ['class' => 'form-control', 'readOnly']) !!}
                    </div>
                    <div class="col">
                        {!! Form::label('last_name', 'Last Name', []) !!}
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'readOnly']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('address', 'Address', []) !!}
                    {!! Form::text('address', null, ['class' => 'form-control', 'readOnly']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('province_id', 'Provinces', []) !!}
                    {!! Form::select('province_id', $provinces, null, ['class' => 'form-control', 'disabled']) !!}
                </div>
                <div class="form-group form-row">
                    <div class="col">
                        {!! Form::label('city_id', 'City', []) !!}
                        {!! Form::select('city_id', $cities, null, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="col">
                        {!! Form::label('postcode', 'PostCode', []) !!}
                        {!! Form::text('postcode', null, ['class' => 'form-control', 'readOnly']) !!}
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col">
                        {!! Form::label('phone', 'Phone', []) !!}
                        {!! Form::text('phone', null, ['class' => 'form-control', 'readOnly']) !!}
                    </div>
                    <div class="col">
                        {!! Form::label('email', 'Email', []) !!}
                        {!! Form::email('email', null, ['class' => 'form-control', 'readOnly']) !!}
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col">
                        {!! Form::label('quantity', 'Quantity', []) !!}
                        {!! Form::text('total_qty', null, ['class' => 'form-control', 'readOnly']) !!}
                    </div>
                    <div class="col">
                        {!! Form::label('total-weight', 'Total Weight(gram)', []) !!}
                        {!! Form::email('total_weight', null, ['class' => 'form-control', 'readOnly']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('track-number', 'Track Number', []) !!}
                    {!! Form::text('track_number', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-block btn-success btn-flat']) !!}
                <a class="btn btn-block btn-flat btn-secondary" href="{{url('admin/orders/' . $shipment->order->id)}}">Back</a>
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
                    <small class="float-right">Date: {{$shipment->order->created_at->format('d/m/Y')}}</small>
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
                        {{ $shipment->order->customer_first_name }} {{ $shipment->order->customer_last_name }}
                        <br> {{ $shipment->order->customer_address }}
                        <br> Email: {{ $shipment->order->customer_email }}
                        <br> Phone: {{ $shipment->order->customer_phone }}
                        <br> Postcode: {{ $shipment->order->customer_postcode }}
                    </address>
                </div>
                <div class="col-sm-6 invoice-col">
                Details
                <address>
                    Invoice ID:
                    <span class="text-dark">#{{ $shipment->order->code }}</span>
                    <br> {{ datetimeFormat($shipment->order->shipment->order_date) }}
                    <br> Status: {{ $shipment->order->status }}
                    <br> Payment Status: {{ $shipment->order->payment_status }}
                    <br> Shipped by: {{ $shipment->order->shipping_service_name }}
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
                            @forelse ($shipment->order->products as $item)
                            <tr style="height:50px;">
                                <td>{{ $item->pivot->sku }}</td>
                                <td>{{ $item->pivot->name }}</td>
                                <td>{{ $item->pivot->qty }}</td>
                                <td>{{ formatRupiah($item->pivot->base_price) }}</td>
                                <td>{{ formatRupiah($item->pivot->sub_total) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">shipment->Order item not found!</td>
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
                                    <td>{{formatRupiah($shipment->order->base_total_price)}}</td>
                                </tr>
                                <tr>
                                    <th>Tax (10%)</th>
                                    <td>{{formatRupiah($shipment->order->tax_amount)}}</td>
                                </tr>
                                <tr>
                                    <th>Shipping:</th>
                                    <td>{{formatRupiah($shipment->order->shipping_cost)}}</td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td>{{formatRupiah($shipment->order->grand_total)}}</td>
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

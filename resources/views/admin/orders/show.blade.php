@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Order Details</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('admin.partials.flash')
        <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
              <div class="col-12">
                <h4>
                  <i class="fas fa-globe"></i> AdminLTE, Inc.
                  <small class="float-right">Date: 10/10/2020</small>
                </h4>
              </div>
              <!-- /.col -->
            </div>

            <!-- info row -->
            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
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
              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                Shipment Address
                <address>
                    {{ $order->shipment->first_name }} {{ $order->shipment->last_name }}
                    <br> {{ $order->shipment->address }}
                    <br> Email: {{ $order->shipment->email }}
                    <br> Phone: {{ $order->shipment->phone }}
                    <br> Postcode: {{ $order->shipment->postcode }}
                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
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
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
              <div class="col-6">
                <p class="lead">Notes From The Orderer : </p>
                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                    {{$order->note ?? 'There is no record'}}
                </p>
              </div>

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

                    @if (!$order->trashed())
                        @if ($order->isPaid() && $order->isConfirmed())
                            <a href="{{ url('admin/shipments/'. $order->shipment->id .'/edit')}}" class="btn btn-block mt-2 btn-lg btn-primary btn-pill"> Procced to Shipment</a>
                        @endif

                        @if (in_array($order->status, [\App\Models\Order::CREATED, \App\Models\Order::CONFIRMED]))
                            <a href="{{ url('admin/orders/'. $order->id .'/cancel')}}" class="btn btn-block mt-2 btn-lg btn-warning btn-pill"> Cancel</a>
                        @endif

                        @if ($order->isDelivered())
                            <a href="#" class="btn btn-block mt-2 btn-lg btn-success btn-pill" onclick="event.preventDefault();
                            document.getElementById('complete-form-{{ $order->id }}').submit();"> Mark as Completed</a>

                            {!! Form::open(['url' => 'admin/orders/complete/'. $order->id, 'id' => 'complete-form-'. $order->id, 'style' => 'display:none']) !!}
                            {!! Form::close() !!}
                        @endif

                        @if (!in_array($order->status, [\App\Models\Order::DELIVERED, \App\Models\Order::COMPLETED]))
                            <a href="#" class="btn btn-block mt-2 btn-lg btn-secondary btn-pill deleteButton" data-id="{{ $order->id }}"> Remove</a>

                            {!! Form::open(['url' => 'admin/orders/'. $order->id, 'class' => 'delete', 'id' => 'delete-form-'. $order->id, 'style' => 'display:none']) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::close() !!}
                        @endif
                    @else
                        <a href="{{ url('admin/orders/restore/'. $order->id)}}" class="btn btn-block mt-2 btn-lg btn-outline-secondary btn-pill restore"> Restore</a>
                        <a href="#" class="btn btn-block mt-2 btn-lg btn-danger btn-pill deleteButton" data-id="{{ $order->id }}"> Remove Permanently</a>

                        {!! Form::open(['url' => 'admin/orders/'. $order->id, 'class' => 'delete', 'id' => 'delete-form-'. $order->id, 'style' => 'display:none']) !!}
                        {!! Form::hidden('_method', 'DELETE') !!}
                        {!! Form::close() !!}
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@stop

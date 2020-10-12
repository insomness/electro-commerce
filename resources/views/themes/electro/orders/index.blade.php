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
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <th>Order ID</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr style="height:50px">
                                        <td>
                                            {{ $order->code }}<br>
                                            <span style="font-size: 12px; font-weight: normal"> {{datetimeFormat($order->order_date) }}</span>
                                        </td>
                                        <td>{{formatRupiah($order->grand_total) }}</td>
                                        <td>{{ $order->status }}</td>
                                        <td>{{ $order->payment_status }}</td>
                                        <td>
                                            <a href="{{ url('orders/'. $order->id) }}" class="btn btn-primary btn-sm">details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $orders->links() }}
                      </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="{{asset('themes/electro/js/checkout.js')}}"></script>
@endpush

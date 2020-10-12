@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Revenue Reports</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('admin.partials.flash')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Responsive Hover Table</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                @include('admin.reports.filter')
                <table class="table table-hover table-stripped text-nowrap" id="datatables" class="display">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Orders</th>
                            <th>Gross Revenue</th>
                            <th>Taxes</th>
                            <th>Shipping</th>
                            <th>Net Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalOrders = 0;
                            $totalGrossRevenue = 0;
                            $totalTaxesAmount = 0;
                            $totalShippingAmount = 0;
                            $totalNetRevenue = 0;
                        @endphp
                        @forelse ($revenues as $revenue)
                            <tr>
                                <td>{{ datetimeFormat($revenue->date, 'd M Y') }}</td>
                                <td>
                                    <a href="{{ url('admin/orders?start='. $revenue->date .'&end='. $revenue->date . '&status=completed') }}">{{ $revenue->num_of_orders }}</a>
                                </td>
                                <td>{{ formatRupiah($revenue->gross_revenue) }}</td>
                                <td>{{ formatRupiah($revenue->taxes_amount) }}</td>
                                <td>{{ formatRupiah($revenue->shipping_amount) }}</td>
                                <td>{{ formatRupiah($revenue->net_revenue) }}</td>
                            </tr>

                            @php
                                $totalOrders += $revenue->num_of_orders;
                                $totalGrossRevenue += $revenue->gross_revenue;
                                $totalTaxesAmount += $revenue->taxes_amount;
                                $totalShippingAmount += $revenue->shipping_amount;
                                $totalNetRevenue += $revenue->net_revenue;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="6">No records found</td>
                            </tr>
                        @endforelse

                        @if ($revenues)
                            <tr>
                                <td>Total</td>
                                <td><strong>{{ $totalOrders }}</strong></td>
                                <td><strong>{{ formatRupiah($totalGrossRevenue) }}</strong></td>
                                <td><strong>{{ formatRupiah($totalTaxesAmount) }}</strong></td>
                                <td><strong>{{ formatRupiah($totalShippingAmount) }}</strong></td>
                                <td><strong>{{ formatRupiah($totalNetRevenue) }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

@stop

@section('js')
@stop

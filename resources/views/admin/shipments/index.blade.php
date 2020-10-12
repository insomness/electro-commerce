@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>List of Shipments</h1>
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
                <table class="table table-hover text-nowrap" id="datatables" class="display">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Total Qty</th>
                            <th>Total Weight</th>
                            <th>Shipping service</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.shipments') }}",
            columns: [
                {data: 'code', name: 'order.code'},
                {data: 'full_name', name: 'full_name', orderable: false},
                {data: 'status', name: 'status'},
                {data: 'total_qty', name: 'total_qty'},
                {data: 'total_weight', name: 'total_weight'},
                {data: 'shipping_service_name', name: 'order.shipping_service_name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [ [0, 'desc'] ]
        });
    });
</script>
@stop

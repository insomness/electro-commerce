@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>List of Orders</h1>
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
                            <th>Grand Total</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Payment</th>
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
            ajax: "{{ route('admin.orders.index') }}",
            columns: [
                {data: 'code', name: 'code'},
                {data: 'grand_total', name: 'grand_total'},
                {data: 'customer_full_name', name: 'customer_full_name', orderable: false},
                {data: 'status', name: 'status'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [ [0, 'desc'] ]
        });
    });
</script>
@stop

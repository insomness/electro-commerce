@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>List Products</h1>
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
                                <th>SKU</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Status</th>
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
                ajax: "{{ route('admin.products.index') }}",
                columns: [
                    {data: 'sku', name: 'sku'},
                    {data: 'name', name: 'name'},
                    {data: 'price', name: 'price'},
                    {data: 'status', name: 'status'},
                    {data: 'action',name: 'action', orderable: false, searchable: false},
                ]
            });
        } );
    </script>

    <script>
        const swalDelete = () => {
            const id = $('.deleteButton').data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    sendData(`products/${id}`, "DELETE", {ajaxId:id})
                    .then(result => window.location.href = result.href )
                    .catch(error => console.log(error));
                }
            });
        }
    </script>
@stop

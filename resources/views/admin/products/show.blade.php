@extends('adminlte::page')

@section('title', 'Product Details')

@section('content_header')
    <h1>Product Details</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('admin.partials.flash')
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Details of Product</h3>
                  <div class="card-tools mr-2">
                        <a href="{{route('admin.products.edit', $product->id)}}" class="mr-3" title="Edit Product">
                            <i class="fas fa-cogs"></i>
                            Edit
                        </a>
                        <a href="javascript:void(0)" title="Delete Product" style="color: red" data-id="{{$product->id}}" onclick="swalDelete();" class="deleteButton mr-3" >
                            <i class="fas fa-trash"></i>
                            Delete
                        </a>
                        {!! Form::open(['method' => 'DELETE', 'url' => 'admin/products/' . $product->id, 'id' => 'form-delete']) !!}
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <table class="table">
                    <tr>
                        <th>SKU</th>
                        <td>{{$product->sku}}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{$product->name}}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{$product->slug}}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{$product->category->name ?? 'uncategory'}}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{{formatRupiah($product->price)}}</td>
                    </tr>
                    <tr class="">
                        <th>Status</th>
                        @if($product->status == 1)
                            <td class="text-success">Active</td>
                        @elseif($product->status == 2)
                            <td class="text-danger">Inactive</td>
                        @else
                            <td class="text-warning">Draft</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{!!$product->description!!}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{$product->created_at->diffForHumans()}}</td>
                    </tr>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

        </div>
    </div>
@stop

@section('js')
    <script>
        const swalDelete = () => {
            const url = "{{route('admin.products.destroy', $product->id)}}";
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
                    document.getElementById('form-delete').submit();
                }
            });
        }
    </script>
@stop

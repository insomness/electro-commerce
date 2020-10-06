@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Images</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-9">
        @include('admin.partials.flash')
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Uploaded at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($product->productImages as $image)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td><img src="{{asset('storage/' . $image->path)}}" style="width: 100px;object-fit: covers"></td>
                        <td>{{$image->created_at}}</td>
                        <td>
                            {!! Form::open(['url' => 'admin/products/'. $image->id . '/images', 'class' => 'delete', 'style' => 'display:inline-block']) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-minus-circle"></i> Remove', ['class' => 'btn btn-danger btn-sm', 'type' => 'submit']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td>This product doesnt have image</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer d-flex justify-content-end ">
                <a href="{{URL::previous()}}" class="btn btn-flat btn-secondary mr-2">
                    <i class="fas fa-long-arrow-alt-left"></i>
                Back
                </a>
                <button type="button" class="btn btn-flat btn-primary" data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-images"></i>
                Add Image
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="categoryLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="categoryLabel">New Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {!! Form::open(['files' => 'true', 'route' => ['admin.products.images.upload', $product->id]]) !!}
            <div class="modal-body">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" multiple name="images[]">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary submit" >Save changes</button>
            </div>
        {!! Form::close() !!}
      </div>
    </div>
</div>
@stop

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init()
        });
    </script>
@endpush

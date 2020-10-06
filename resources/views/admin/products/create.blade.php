@extends('adminlte::page')
@php
   $formTitle = !empty($product) ?  'Update' : $formTitle = 'New';
@endphp
@section('title', $formTitle . ' Product')
@section('content_header')
    <h1>Add Product</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-9">
        @include('admin.partials.flash')
        <div class="card">
            @if (!empty($product))
                {!! Form::model($product, ['url' => ['admin/products', $product->id], 'method' => 'PUT']) !!}
            @else
                {!! Form::open(['url' => 'admin/products', 'method' => 'post']) !!}
            @endif
            {{ csrf_field() }}
            <div class="card-body">
                <div class="form-group">
                    {!! Form::label('sku', 'SKU') !!}
                    {!! Form::text('sku', old('sku'), ['class' => 'form-control', 'placeholder' => 'Example: SG-G53-LP-ROG', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'ROG Strix G G531GD', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('price', 'Price') !!}
                    {!! Form::text('price', old('price'), ['class' => 'form-control', 'placeholder' => '1500000','required', 'id' => 'price']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('category_id', 'Category') !!}
                    {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'custom-select', 'placeholder' => '-- Set Category --']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('description', 'Description') !!}
                    {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'id' => 'summernote']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', $statuses , old('status'), ['class' => 'form-control', 'placeholder' => '-- Set Status --', 'required']) !!}
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{URL::previous()}}" class="btn btn-secondary btn-default">Back</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    @if ( Request::segment(4) == 'edit' )
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Product Menu</h4>
                </div>
                <div class="card-body d-flex flex-column">
                    <a href="{{route('admin.products.show', $product->id)}}">Product Detail</a>
                    <a href="{{route('admin.products.images', $product->id)}}">Product Images</a>
                </div>
            </div>
        </div>
    @endif
</div>
@stop

@section('js')
    <script>
        rupiahFormatOnDom('price');
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 200,
                tabSize: 2,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ],
            });
        });
    </script>
@endsection

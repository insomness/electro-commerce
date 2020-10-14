@extends('themes.electro.app')
@section('content')
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- ASIDE -->
            <div id="aside" class="col-md-3">
                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">Categories</h3>
                    <div class="checkbox-filter">

                        <div class="input-checkbox">
                            <input type="checkbox" id="category-1">
                            <label for="category-1">
                                <span></span>
                                Laptops
                                <small>(120)</small>
                            </label>
                        </div>

                        <div class="input-checkbox">
                            <input type="checkbox" id="category-2">
                            <label for="category-2">
                                <span></span>
                                Smartphones
                                <small>(740)</small>
                            </label>
                        </div>

                        <div class="input-checkbox">
                            <input type="checkbox" id="category-3">
                            <label for="category-3">
                                <span></span>
                                Cameras
                                <small>(1450)</small>
                            </label>
                        </div>

                        <div class="input-checkbox">
                            <input type="checkbox" id="category-4">
                            <label for="category-4">
                                <span></span>
                                Accessories
                                <small>(578)</small>
                            </label>
                        </div>

                        <div class="input-checkbox">
                            <input type="checkbox" id="category-5">
                            <label for="category-5">
                                <span></span>
                                Laptops
                                <small>(120)</small>
                            </label>
                        </div>

                        <div class="input-checkbox">
                            <input type="checkbox" id="category-6">
                            <label for="category-6">
                                <span></span>
                                Smartphones
                                <small>(740)</small>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- /aside Widget -->

                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">Price</h3>
                    {!! Form::open(['method' => 'get']) !!}
                    <div class="price-filter">
                        <div id="price-slider"></div>
                        <div class="input-number price-min">
                            <input id="price-min" type="text" name="price-min" class="input">
                        </div>
                        <span>-</span>
                        <div class="input-number price-max" style="margin-bottom: 10px">
                            <input id="price-max" type="text" name="price-max" class="input">
                        </div>
                        {!! Form::submit('Search', ['class' => 'primary-btn order-submit']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- /aside Widget -->

            </div>
            <!-- /ASIDE -->

            <!-- STORE -->
            <div id="store" class="col-md-9">
                <!-- store top filter -->
                <div class="store-filter clearfix">
                    <div class="store-sort">
                    <label>Sort By : </label>
                        {{ Form::select('sort', $sorts , $selectedSort ,array('onChange' => 'this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);', 'class' => 'input-select')) }}

                        <label>
                            Show:
                        </label>
                        <select class="input-select" id="pagination">
                            <option value="10" @if($items == 10) selected @endif >10</option>
                            <option value="25" @if($items == 25) selected @endif >25</option>
                            <option value="50" @if($items == 50) selected @endif >50</option>
                        </select>
                    </div>
                </div>
                <!-- /store top filter -->

                <!-- store products -->
                <div class="row">
                    <!-- product -->
                    @foreach ($products as $product)
                    <div class="col-md-4 col-xs-6">
                        <div class="product">
                            <div class="product-img">
                                <img src="{{asset('storage/'. $product->productImages[0]->path)}}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{$product->category->name}}</p>
                                <h3 class="product-name"><a href="/products/{{$product->slug}}">{{$product->name}}</a></h3>
                                <h4 class="product-price">Rp. {{number_format($product->price, null ,',','.')}}</h4>
                                <div class="product-btns">
                                    <button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
                                    <button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
                                </div>
                            </div>
                            <div class="add-to-cart">
                                <button class="add-to-cart-btn" data-productId="{{$product->id}}"><i class="fa fa-shopping-cart"></i> add to cart</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <!-- /product -->
                </div>
                <!-- /store products -->

                <!-- store bottom filter -->
                <div class="store-filter clearfix">
                    {{ $products->links() }}
                </div>
                <!-- /store bottom filter -->
            </div>
            <!-- /STORE -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>

@endsection

@push('js')
<script>
    document.getElementById('pagination').onchange = function() {
        window.location = "/products?items=" + this.value;
    };
</script>
@endpush

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <!-- section title -->
            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">New Products</h3>
                    <div class="section-nav">
                        <ul class="section-tab-nav tab-nav">
                            <?php $isFirst = true ?>
                            @foreach ($topCategoryList as $cl)
                            <li>
                                <a id="{{ $isFirst ? 'clicked' : ''}}"  data-toggle="tab" href="#{{$cl->slug}}">{{$cl->name}}</a>
                            </li>
                            <?php $isFirst = false ?>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /section title -->

            <!-- Products tab & slick -->
            <div class="col-md-12">
                <div class="row">
                    <div class="products-tabs">
                        @foreach ($topCategoryList as $cl)
                        <div id="{{$cl->slug}}" class="tab-pane active">
                            <div class="products-slick responsive" data-nav="#slick-nav-{{$cl->slug}}">
                                @foreach ($cl->products as $product)
                                <div class="product">
                                    <div class="product-img">
                                        <img src="{{asset('storage/'. $product->productImages[0]->path)}}" style="object-fit: cover">
                                        <div class="product-label">
                                            <span class="sale">-30%</span>
                                            <span class="new">NEW</span>
                                        </div>
                                    </div>
                                    <div class="product-body">
                                        <p class="product-category">{{$product->category->name}}</p>
                                        <h3 class="product-name"><a href="products/{{$product->slug}}">{{$product->name}}</a></h3>
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
                                @endforeach
                            </div>
                            <div id="slick-nav-{{$cl->slug}}" class="products-slick-nav"></div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Products tab & slick -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

@push('js')
    <script>
      $('#clicked')[0].click();
    </script>
@endpush

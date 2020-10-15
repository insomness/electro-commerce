@extends('themes.electro.app')
@section('content')
    		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- Product main img -->
					<div class="col-md-5 col-md-push-2">
						<div id="product-main-img">
                            @foreach ($product->productImages as $image)
							<div class="product-preview">
								<img src="{{asset('storage/' . $image->path)}}" alt="">
                            </div>
                            @endforeach
						</div>
					</div>
					<!-- /Product main img -->

					<!-- Product thumb imgs -->
					<div class="col-md-2  col-md-pull-5">
						<div id="product-imgs">
                            @foreach ($product->productImages as $image)
							<div class="product-preview">
								<img src="{{asset('storage/' . $image->path)}}" alt="">
                            </div>
                            @endforeach
						</div>
					</div>
					<!-- /Product thumb imgs -->

					<!-- Product details -->
					<div class="col-md-5">
						<div class="product-details">
							<h2 class="product-name">{{$product->name}}</h2>
							<div>
                                <div>
                                    <div class="product-rating">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < number_format($product->averageRating, 2))
                                                <i class="fa fa-star"></i>
                                            @else
                                                <i class="fa fa-star-o"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <a class="review-link" href="#">{{$product->timesRated()}} Review(s)  @if ($isUserHasBought ?? '') | Add your review< @endif </a>
                                </div>
                                <div>
                                    <h3 class="product-price">{{formatRupiah($product->price)}}</h3>
                                    <span class="product-available">
                                        @if ($product->status == 1)
                                            In Stock
                                        @else
                                            Out of Stock
                                        @endif
                                    </span>
                                </div>
							</div>
							<p>{!! $product->description !!}</p>

							<div class="add-to-cart">
								<div class="qty-label">
									Qty
									<div class="input-number">
										<input type="number" id="cart-quantity">
										<span class="qty-up">+</span>
										<span class="qty-down">-</span>
									</div>
								</div>
								<button class="add-to-cart-btn" data-productId="{{$product->id}}"><i class="fa fa-shopping-cart"></i> add to cart</button>
							</div>

							<ul class="product-btns">
								<li><a href="#"><i class="fa fa-heart-o"></i> add to wishlist</a></li>
								<li><a href="#"><i class="fa fa-exchange"></i> add to compare</a></li>
							</ul>

							<ul class="product-links">
                                <li>Category:</li>
                                <li><a href="{{$product->category->slug}}">{{$product->category->name}}</a></li>
							</ul>

							<ul class="product-links">
								<li>Share:</li>
								<li><a href="#"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#"><i class="fa fa-twitter"></i></a></li>
								<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
								<li><a href="#"><i class="fa fa-envelope"></i></a></li>
							</ul>

						</div>
					</div>
					<!-- /Product details -->

					<!-- Product tab -->
					<div class="col-md-12">
						<div id="product-tab">
							<!-- product tab nav -->
							<ul class="tab-nav">
								<li class="active"><a data-toggle="tab" href="#tab1">Description</a></li>
								<li><a data-toggle="tab" href="#tab2">Reviews</a></li>
							</ul>
							<!-- /product tab nav -->

							<!-- product tab content -->
							<div class="tab-content">
								<!-- tab1  -->
								<div id="tab1" class="tab-pane fade in active">
									<div class="row">
										<div class="col-md-12">
											<p>{!! $product->description !!}</p>
										</div>
									</div>
								</div>
								<!-- /tab1  -->

                                <div id="tab2" class="tab-pane fade in">
									<div class="row">
										<!-- Rating -->
										<div class="col-md-3">
											<div id="rating">
												<div class="rating-avg">
													<span>{{number_format($product->averageRating, 2)}}</span>
													<div class="rating-stars">
														@for ($i = 0; $i < 5; $i++)
                                                            @if ($i < number_format($product->averageRating, 2))
                                                                <i class="fa fa-star"></i>
                                                            @else
                                                                <i class="fa fa-star-o"></i>
                                                            @endif
                                                        @endfor
													</div>
												</div>
												<ul class="rating">
                                                    @for ($i = 5; $i > 0; $i--)
                                                        <li>
                                                            <div class="rating-stars">
                                                                @for ($j = 0; $j < $i; $j++)
                                                                    <i class="fa fa-star"></i>
                                                                @endfor
                                                            </div>
                                                            <div class="rating-progress">
                                                                <div style="width: {{$product->ratingPercentByValue($i)}}%;"></div>
                                                            </div>
                                                            <span class="sum">{{$product->ratings()->where('rating', $i)->get()->count()}}</span>
                                                        </li>
                                                    @endfor
												</ul>
											</div>
										</div>
										<!-- /Rating -->

										<!-- Reviews -->
										<div class="col-md-6">
											<div id="reviews">
												<ul class="reviews">
                                                    @foreach ($product->ratings as $rating)
													<li>
														<div class="review-heading">
															<h5 class="name">{{$rating->user->adminlte_desc()}}</h5>
															<p class="date">{{$rating->created_at->format('D, M Y h:i A')}}</p>
															<div class="review-rating">
                                                                @for ($i = 0; $i < 5; $i++)
                                                                    @if ($i < $rating->rating)
                                                                        <i class="fa fa-star"></i>
                                                                    @else
                                                                        <i class="fa fa-star-o empty"></i>
                                                                    @endif
                                                                @endfor
															</div>
														</div>
														<div class="review-body">
															<p>{{$rating->comment}}</p>
														</div>
                                                    </li>
                                                    @endforeach

												</ul>
											</div>
										</div>
										<!-- /Reviews -->

                                        @if ($isUserHasBought ?? '')
										<!-- Review Form -->
										<div class="col-md-3">
											<div id="review-form">
                                                @include('themes.electro.products.review')
											</div>
										</div>
                                        <!-- /Review Form -->
                                        @endif
									</div>
								</div>

							</div>
							<!-- /product tab content  -->
						</div>
					</div>
					<!-- /product tab -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		<!-- Section -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<div class="col-md-12">
						<div class="section-title text-center">
							<h3 class="title">Related Products</h3>
						</div>
					</div>

                    <!-- product -->
                    @foreach ($product->category->products as $item)
					<div class="col-md-3 col-xs-6">
						<div class="product">
							<div class="product-img">
								<img src="{{asset('storage/' . $item->productImages[0]->path)}}" alt="">
							</div>
							<div class="product-body">
								<p class="product-category">{{$item->category->name}}</p>
								<h3 class="product-name"><a href="{{$item->slug}}">{{$item->name}}</a></h3>
								<h4 class="product-price">{{formatRupiah($item->price)}}</h4>
								<div class="product-rating">
								</div>
								<div class="product-btns">
									<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
									<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
									<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
								</div>
							</div>
							<div class="add-to-cart">
								<button class="add-to-cart-btn" data-productId="{{$item->id}}"><i class="fa fa-shopping-cart"></i> add to cart</button>
							</div>
						</div>
					</div>
                    @endforeach
					<!-- /product -->

				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /Section -->
@endsection

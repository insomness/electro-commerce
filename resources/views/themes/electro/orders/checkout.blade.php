@extends('themes.electro.app')
@section('content')
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            @include('themes.electro.partials.flash')
            <div class="col-md-7">
                {!! Form::model($user, ['url' => '/orders/do-checkout', 'method' => 'post']) !!}
                <!-- Billing Details -->
                <div class="billing-details">
                    <div class="section-title">
                        <h3 class="title">Billing address</h3>
                    </div>
                    <div class="form-group">
                        {!! Form::text('first_name', null, ['placeholder' => 'First Name', 'class' => 'input']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::text('last_name', null, ['placeholder' => 'Last Name', 'class' => 'input']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'input']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::text('address', null, ['placeholder' => 'Address', 'class' => 'input']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::select('province_id', $provinces, null, ['class' => 'form-control', 'id' => 'province_id']) !!}
                    </div>
                    <div class="form-group">
                          {!! Form::select('city_id', $cities, null, ['class' => 'form-control', 'id' => 'city_id']) !!}
                          <small>* if Courier option doesnt show up, try to select other city and reselect yours</small>
                    </div>
                    <div class="form-group">
                        {!! Form::text('postcode', null, ['placeholder' => 'Post Code', 'class' => 'input', 'id' => 'postcode']) !!}
                    </div>
                    <div class="form-group">
                        <input type="tel" id="phone" value="{{$user->phone}}" name="phone" placeholder="Phone Number" pattern="[0-9]{11,14}" class="input" required>
                    </div>
                </div>
                <!-- /Billing Details -->

                <!-- Shiping Details -->
                <div class="shiping-details">
                    <div class="section-title">
                        <h3 class="title">Shiping address</h3>
                    </div>
                    <div class="input-checkbox">
                        <input type="checkbox" id="shiping-address" name="ship_to">
                        <label for="shiping-address">
                            <span></span>
                            Ship to a diffrent address?
                        </label>
                        <div class="caption">
                            <div class="form-group">
                                {!! Form::text('shipping_firstname', old('shipping_firstname'), ['placeholder' => 'First Name', 'class' => 'input']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::text('shipping_lastname', old('shipping_lastname'), ['placeholder' => 'Last Name', 'class' => 'input']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::text('shipping_email', old('shipping_email'), ['placeholder' => 'Email', 'class' => 'input']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::text('shipping_address', old('shipping_address'), ['placeholder' => 'Address', 'class' => 'input']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::select('shipping_province', $provinces, null, ['class' => 'form-control', 'id' => 'shipping_province']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::select('shipping_city', ['' => ''], null, ['class' => 'form-control', 'id' => 'shipping_city']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::text('shipping_postcode', old('shipping_postcode'), ['placeholder' => 'Post Code', 'class' => 'input', 'id' => 'shipping_postcode']) !!}
                            </div>
                            <div class="form-group">
                                <input type="tel" id="phone" name="shipping_phone" placeholder="Phone Number" pattern="[0-9]{11,14}" class="input">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Shiping Details -->

                <!-- Order notes -->
                <div class="order-notes">
                    <textarea class="input" placeholder="Order Notes" name="note"></textarea>
                </div>
                <!-- /Order notes -->
            </div>

            <!-- Order Details -->
            <div class="col-md-5 order-details">
                <div class="section-title text-center">
                    <h3 class="title">Your Order</h3>
                </div>
                <div class="order-summary">
                    <div class="order-col">
                        <div><strong>PRODUCT</strong></div>
                        <div><strong>TOTAL</strong></div>
                    </div>
                    <div class="order-products">
                        @foreach ($carts as $cart)
                        <div class="order-col">
                            <div>{{$cart->quantity}}x {{$cart->name}}</div>
                            <div>{{formatRupiah($cart->price)}}</div>
                        </div>
                        @endforeach
                    </div>
                    <div class="order-col">
                        <div><strong>Tax(10%)</strong></div>
                        <div>{{formatRupiah(\Cart::getCondition('TAX 10%')->getCalculatedValue(\Cart::getSubTotal()))}}</div>
                    </div>
                    <div class="order-col">
                        <div><strong>SHIPPING</strong> ({{$totalWeight}} Kg)</div>
                        <div></div>
                    </div>
                    <div class="order-col">
                        {!! Form::select('shipping_service', [], null, ['class' => 'input', 'id' => 'kurir', 'placeholder' => 'Courier']) !!}
                    </div>
                    <div class="order-col">
                        <div><strong>TOTAL</strong></div>
                        <div><strong class="order-total" style="display: inline-block;width: max-content;margin-top: 20px" >{{formatRupiah(\Cart::getTotal())}}</strong></div>
                    </div>
                </div>
                {!! Form::submit('Place order', ['class' => 'primary-btn order-submit']) !!}
            </div>
            <!-- /Order Details -->
            {!! Form::close() !!}
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
@endsection

@push('js')
<script src="{{asset('themes/electro/js/checkout.js')}}"></script>
@endpush

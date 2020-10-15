<div class="col-md-3 clearfix" style="margin-top: 20px">
    <div class="header-ctn">
        <div>
            <a href="#">
                <i class="fa fa-heart-o"></i>
                <span>Your Wishlist</span>
                <div class="qty">2</div>
            </a>
        </div>


        <div class="dropdown">

            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                <i class="fa fa-shopping-cart"></i>
                <span>Your Cart</span>
                <div class="qty cartCount">{{\Cart::getTotalQuantity()}}</div>
            </a>

            <div class="cart-dropdown">
                <div class="cart-list">
                    @foreach (\Cart::getContent() as $cart)
                    <div class="product-widget"  data-id="{{$cart->id}}">
                        <div class="product-img">
                            <img src="{{asset('storage/' . $cart->associatedModel->productImages[0]->path ?? '')}}">
                        </div>
                        <div class="product-body">
                            <h3 class="product-name"><a href="#">{{$cart->name}}</a></h3>
                            <h4 class="product-price"><span class="qty">{{$cart->quantity}}x</span>Rp. {{number_format($cart->price, null ,',','.')}}</h4>
                        </div>
                        <button class="delete"><i class="fa fa-close"></i></button>
                    </div>
                    @endforeach
                </div>
                <div class="cart-summary">
                    <small class="cartCount">{{\Cart::getTotalQuantity()}} Item(s) selected</small>
                    <h5 class="subTotal">SUBTOTAL: Rp. {{number_format(\Cart::getSubTotal(), null ,',','.')}}</h5>
                </div>
                <div class="cart-btns">
                    <a href="/carts">View Cart</a>
                    <a href="{{route('orders.checkout')}}">Checkout  <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="menu-toggle">
                <a href="#">
                    <i class="fa fa-bars"></i>
                    <span>Menu</span>
                </a>
            </div>
        </div>
    </div>
</div>

@extends('themes.electro.app')
@section('content')
<div class="container" style="margin: 50px auto;">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-5"><i class="fa fa-shopping-cart"></i> Shooping Cart</h2>
            <hr>
            <h4 class="mt-5">{{$carts->count()}} items(s) in Shopping Cart</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table">
                @foreach ($carts as $cart)
                    <tr class="cart-{{$cart->id}}">
                        <td>
                            <img src="{{asset('storage/' . $cart->associatedModel->productImages[0]->path)}}" style="object-fit: cover" height="100px">
                        </td>
                        <td style="vertical-align: middle">
                            <strong>{{$cart->name}}</strong>
                            <br>
                            <small class="weight">
                                Total Weight : {{$cart->associatedModel->weight * $cart->quantity / 1000 }} Kg<br>
                            </small>
                        </td>
                        <td style="vertical-align: middle">
                            <a href='javascript:void(0)' onclick="document.getElementById('remove').submit()">Remove</a>
                            {!! Form::open(['route' => ['carts.destroy', $cart->id], 'method' => 'DELETE', 'style' => 'display:none', 'id' => 'remove']) !!}
                            {!! Form::close() !!}
                            <br>
                            <a href="">Save For Later</a>
                        </td>
                        <td style="vertical-align: middle">
                            <select class="form-control" style="width: 80px" data-id="{{$cart->id}}">
                                @for ($i = 1; $i <= 50; $i++)
                                    <option value={{$i}} {{$i == $cart->quantity ? 'selected' : ''}}>{{$i}}</option>
                                @endfor
                            </select>
                        </td>
                        <td style="vertical-align: middle" class="price">
                            <span class="amountofunit">Rp. {{number_format($cart->price * $cart->quantity, null ,',','.')}}</span>
                            <br>
                            (Rp. {{number_format($cart->price, null ,',','.')}} / Unit)
                        </td>
                    </tr>
                @endforeach
            </table>
            <a href="{{url('/orders/checkout')}}" class="primary-btn order-submit pull-right checkout">Checkout</a>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $('.table tr .form-control').on('change', function(){
        const productId = $(this).data('id');
        const quantityChanged = parseInt($(this).val());

        $.ajax({
        url: 'carts',
        dataType: 'json',
        type: 'PATCH',
        data: {
            productId: productId,
            quantityChanged: quantityChanged,
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            cartList(data);
        },
        error: function(xhr, ajaxOption, thrownError){
            if(xhr.status == 401){
                window.location.href = "{{route('login')}}"
            }
        }
    });
});


function cartList(data){
        let {cart} = data;
        $(`.cart-${cart.id} .price .amountofunit`).html('Rp. ' + numberWithCommas(cart.price * cart.quantity));
        $(`.product-widget[data-id=${cart.id}] .product-price .qty`).html(`${cart.quantity} x`);

        if(cart.associatedModel.weight * cart.quantity / 1000 > 30){
            $(`.cart-${cart.id} .weight`).html(`Exceed the quota limit, cannot continue payment`);
            $(`.checkout`).css('display', 'none');
        }else{
            $(`.cart-${cart.id} .weight`).html(`Total Weight: ${cart.associatedModel.weight * cart.quantity / 1000} Kg`);
            $(`.checkout`).css('display', '');
        }
}
</script>
@endpush

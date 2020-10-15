{!! Form::model($product->ratings()->where('user_id', Auth::id())->first(),['url' => 'products/'. $product->id .'/review', 'class' => 'review-form']) !!}
{!! Form::textarea('comment', null, ['class' => 'input', 'placeholder' => 'Your Review', 'required']) !!}
    <div class="input-rating">
        <span>Your Rating: </span>
        <div class="stars">
            <input id="star5" {{$product->userSumRating == 5 ? 'checked' : null}} name="rating" value="5" type="radio"><label for="star5"></label>
            <input id="star4" {{$product->userSumRating == 4 ? 'checked' : null}} name="rating" value="4" type="radio"><label for="star4"></label>
            <input id="star3" {{$product->userSumRating == 3 ? 'checked' : null}} name="rating" value="3" type="radio"><label for="star3"></label>
            <input id="star2" {{$product->userSumRating == 2 ? 'checked' : null}} name="rating" value="2" type="radio"><label for="star2"></label>
            <input id="star1" {{$product->userSumRating == 1 ? 'checked' : null}} name="rating" value="1" type="radio"><label for="star1"></label>
        </div>
    </div>
    {!! Form::submit('Submit', ['class' => 'primary-btn']) !!}
{!! Form::close() !!}

@extends('themes.electro.app')
@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                @include('themes.electro.partials.flash')
                <div class="col-md-3">
                    @include('themes.electro.partials.user_menu')
                </div>
                <div class="col-md-9">
                    {!! Form::model($user, ['url' => '/profiles', 'method' => 'post']) !!}
                    <div class="billing-details">
                        <div class="section-title">
                            <h3 class="title">Profile Account</h3>
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
                        </div>
                        <div class="form-group">
                            {!! Form::text('postcode', null, ['placeholder' => 'Post Code', 'class' => 'input', 'id' => 'postcode']) !!}
                        </div>
                        <div class="form-group">
                            <input type="tel" id="phone" value="{{$user->phone}}" name="phone" placeholder="Phone Number" pattern="[0-9]{11,14}" class="input" required>
                        </div>
                        {!! Form::submit('Update Profile', ['class' => 'primary-btn order-submit pull-right', 'style' => 'margin-top: 10px']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="{{asset('themes/electro/js/checkout.js')}}"></script>
@endpush

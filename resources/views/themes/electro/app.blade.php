<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<title>Electro - HTML Ecommerce Template</title>

        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<link type="text/css" rel="stylesheet" href="{{asset("themes/electro/css/bootstrap.min.css")}}"/>
		<link type="text/css" rel="stylesheet" href="{{asset("themes/electro/css/slick.css")}}"/>
		<link type="text/css" rel="stylesheet" href="{{asset("themes/electro/css/slick-theme.css")}}"/>
		<link type="text/css" rel="stylesheet" href="{{asset("themes/electro/css/nouislider.min.css")}}"/>
		<link rel="stylesheet" href="{{asset("themes/electro/css/font-awesome.min.css")}}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
        <link type="text/css" rel="stylesheet" href="{{asset("themes/electro/css/style.css")}}"/>

    </head>
	<body>

        @include('themes.electro.partials.header')
        @include('themes.electro.partials.navigation')
        @yield('content')
        @include('themes.electro.partials.newslater')
        @include('themes.electro.partials.footer')
        <div class="overlay"></div>

		<script src="{{asset("themes/electro/js/jquery.min.js")}}"></script>
		<script src="{{asset("themes/electro/js/bootstrap.min.js")}}"></script>
		<script src="{{asset("themes/electro/js/slick.min.js")}}"></script>
		<script src="{{asset("themes/electro/js/nouislider.min.js")}}"></script>
		<script src="{{asset("themes/electro/js/jquery.zoom.min.js")}}"></script>
        <script src="{{asset("themes/electro/js/wNumb.min.js")}}"></script>
        <script src="{{asset("themes/electro/js/main.js")}}"></script>
        <script src="{{asset("themes/electro/js/myCart.js")}}"></script>
        <script src="{{asset("themes/electro/js/jquery.countdown.min.js")}}"></script>
        <script src="{{asset('js/app.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
        @stack('js')
	</body>
</html>


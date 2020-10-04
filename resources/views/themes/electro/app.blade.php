<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Electro - HTML Ecommerce Template</title>

        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<link type="text/css" rel="stylesheet" href="{{asset("themes/electro/css/bootstrap.min.css")}}"/>
		<link type="text/css" rel="stylesheet" href="{{asset("themes/electro/css/slick.css")}}"/>
		<link type="text/css" rel="stylesheet" href="{{asset("themes/electro/css/slick-theme.css")}}"/>
		<link type="text/css" rel="stylesheet" href="{{asset("themes/electro/css/nouislider.min.css")}}"/>
		<link rel="stylesheet" href="{{asset("themes/electro/css/font-awesome.min.css")}}">
		<link type="text/css" rel="stylesheet" href="{{asset("themes/electro/css/style.css")}}"/>
    </head>
	<body>

        @include('themes.electro.partials.header')
        @include('themes.electro.partials.navigation')
        @yield('content')
        @include('themes.electro.partials.newslater')
        @include('themes.electro.partials.footer')

		<script src="{{asset("themes/electro/js/jquery.min.js")}}"></script>
		<script src="{{asset("themes/electro/js/bootstrap.min.js")}}"></script>
		<script src="{{asset("themes/electro/js/slick.min.js")}}"></script>
		<script src="{{asset("themes/electro/js/nouislider.min.js")}}"></script>
		<script src="{{asset("themes/electro/js/jquery.zoom.min.js")}}"></script>
        <script src="{{asset("themes/electro/js/main.js")}}"></script>
        <script src="{{asset('js/app.js')}}"></script>
        @stack('js')
	</body>
</html>


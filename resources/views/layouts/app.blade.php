<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
	<noscript>Javascript est désactivé dans votre navigateur. Vous risquez de ne pas avoir accès à toutes les fonctionnalités du site internet.</noscript>
	<!--[if lt IE 11]><div class="browsehappy"><div class="container"><p>Savez-vous que votre navigateur est obsolète ?</p><p>Pour naviguer de la manière la plus satisfaisante sur le Web, nous vous recommandons de procéder à une <a href="http://windows.microsoft.com/ie">mise à jour de votre navigateur</a>.<br>Vous pouvez aussi <a href="http://browsehappy.com/">essayer d’autres navigateurs web populaires</a>.</p></div></div><![endif]-->
    @include('front._header')
    @include('front._menu')

	<div class="container main-wrapper">
		@yield('breadcrumb')
		@yield('content')
	</div>

	@include('front._footer')

	@yield('after-content')

	<script src="{{ asset('js/bko.js') }}"></script>
	@stack('inline-script')
</body>
</html>

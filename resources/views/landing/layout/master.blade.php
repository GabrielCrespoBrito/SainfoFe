<!DOCTYPE html>
<html class="wide wow-animation" lang="en">

<head>
  <title>{{ $title ?? 'Sainfo - Facturaciòn Electronica' }}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Sainfo - Factura Electrónica Sunat Perú" />
  <meta name="keywords" content="Factura, Electrónica, Sainfo, ERP, Perú, Sunat, PERU, Boleta, Venta, Nota, Crédito, Débito, Comprobante, Retención, Percepción, Guía, Remisión" />
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="sitemap" type="application/xml" href="/sitemap.xml">
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('images/icons/apple-icon-57x57.png') }}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('images/icons/apple-icon-60x60.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('images/icons/apple-icon-72x72.png') }}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/icons/apple-icon-76x76.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('images/icons/apple-icon-114x114.png') }}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/icons/apple-icon-120x120.png') }}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/icons/apple-icon-144x144.png') }}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/icons/apple-icon-152x152.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icons/apple-icon-180x180.png') }}">
  <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('images/icons/android-icon-192x192.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons//favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/icons//favicon-96x96.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icons//favicon-16x16.png') }}">


  <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,900">
  <link rel="stylesheet" href="{{ asset('page/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('page/css/fonts.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('page/css/style.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset(mix('page/css/mix/style.css')) }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
  @yield('css')
  <style>
    .ie-panel {
      display: none;
      background: #212121;
      padding: 10px 0;
      box-shadow: 3px 3px 5px 0 rgba(0, 0, 0, .3);
      clear: both;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    html.ie-10 .ie-panel,
    html.lt-ie-10 .ie-panel {
      display: block;
    }
  </style>
</head>

<body>
  <div class="ie-panel"><a href="http://windows.microsoft.com/en-US/internet-explorer/"><img src="page/images/ie8-panel/warning_bar_0000_us.jpg" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."></a></div>

  @include('landing.layout.header')

  @yield('content')

  @include('landing.layout.footer')

  </div>

  <div class="snackbars" id="form-output-global"></div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.js"></script>
  <script src="{{ asset('page/js/core.min.js') }}"></script>
  <script src="{{ asset('page/js/script.js') }}"></script>
  @yield('js')
  <!--coded by Starlight-->

  <div id="simple-chat-button--container">
    <a id="simple-chat-button--button" target="_blank" href="https://web.whatsapp.com/send?phone=+51936525581&amp;text=Hola%2C+quisiera+informaci%C3%B3n+de+sus+productos%2Fservicios"></a>
    <span id="simple-chat-button--text"></span>
  </div>


</body>

</html>
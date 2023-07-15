<!DOCTYPE html>
<html lang="en">
<style>
</style>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta charset="UTF-8">
  <title>{{ $title }} </title>
  <style>
    @include('ventas.partials.pdf.style_a4_a5')
  </style>
  <script type="text/javascript" src="{{ asset('js/numero_palabra.js')}}"></script>
  <style>
    .container {
      font-family: 'Helvetica';
    }
  </style>
</head>

<body>
  <?php $is_boleta = false; ?>
  <div class="container {{ $formato_small ? 'small' : '' }} " style="font-family: 'Helvetica' !important ">
    @include('ventas.partials.pdf.a42.cabezera', [
    'formato_small' => $formato_small
    ])
    @include('ventas.partials.pdf.a4.cliente')

    <div class="lipo" style="">
      @include('ventas.partials.pdf.a4.items')
    </div>

  </div>
</body>

</html>
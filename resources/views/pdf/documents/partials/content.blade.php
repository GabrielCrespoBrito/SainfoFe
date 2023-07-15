@php
  $preliminar = $preliminar ?? false;
  $logoMarcaAgua = $logoMarcaAgua ?? null;
@endphp
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<style>
  @include('pdf.documents.partials.css', [ 'logoMarcaAgua' => $logoMarcaAgua, 'preliminar' => $preliminar ])
</style>
</head>
<body class="{{ $class_name ?? '' }} {{ $preliminar ? 'preliminar' : '' }} ">
  @if( $logoMarcaAgua )
    @php
      $top = $logoMarcaAguaSizes['top'];
      $left = $logoMarcaAguaSizes['left'];
    @endphp
    <img class="img-marca-agua" style="top:{{$top}}px;left:{{$left}}px" src="data:image/png;base64,{{ $logoMarcaAgua }}">
  @endif

  <div style="display:block" class="container">  
    @if($preliminar)
    @endif
  
  {{ $content }}
  
  </div>
</body>
</html>
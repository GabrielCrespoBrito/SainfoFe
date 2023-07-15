<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title> {{ $nombre_reporte }} </title>
  @include('components.reportes.reporte_basico.css')
</head>
<body style="width:50%">
  @include('cajas.partials.movimientos.reporte_mov_egreso.cabecera')
  @include('cajas.partials.movimientos.reporte_mov_egreso.contenido')
  @include('cajas.partials.movimientos.reporte_mov_egreso.pie_firma')
</body>
</html>
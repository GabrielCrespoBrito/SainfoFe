@php
$tableInHtml = false;
@endphp

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Documento</title>
</head>
<style type="text/css">
  @include('reportes.utilidades_ventas.pdf.css')
</style>
<body>
  @include('reportes.utilidades_ventas.pdf.header', [ 'local' => $local , 'grupo' => $grupo ])
  @include('reportes.ganancias.partials.table_dias', ['class_table' => 'table_items' ])
</body>
</html>
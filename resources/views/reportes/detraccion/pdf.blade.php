<html>
<head>
  <meta charset="utf-8">
  <title>Documento</title>
</head>
<style type="text/css">
  @include('reportes.ventas_mensual.partials.css')
</style>
<body>

@include('reportes.detraccion.partials.header', [
  'data_report' => $data_report, 
  'nombre_empresa' => $data_report['nombre_empresa'],  
  'ruc_empresa' => $data_report['ruc_empresa'],
  'periodo' => $data_report['periodo'],
  ])
@include('reportes.detraccion.partials.table', ['data_report' => $data_report ])

</body>
</html>

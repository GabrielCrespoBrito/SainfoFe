<html>
<head>
  <meta charset="utf-8">
  <title>Documento</title>
</head>
<style type="text/css">
  @include('reportes.ventas_mensual.partials.css')
</style>
<body>

@include('reportes.inventario_valorizado.partials.header', [
  'data_report' => $data_report, 
  'nombre_empresa' => $data_report['nombre_empresa'],  
  'ruc_empresa' => $data_report['ruc_empresa'],
  ])
@include('reportes.inventario_valorizado.partials.table', ['data_report' => $data_report['data'], 'total_general' => $data_report['total_general'] ])
</body>
</html>

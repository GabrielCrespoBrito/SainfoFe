<!DOCTYPE html>
<html>
<head>
  <title>Documento</title>
</head>
<style type="text/css">
  @include('reportes.partials.css.productos_clientes')
</style>
<body>
  
  @include('reportes.mejores_clientes.pdf.header')
  @include('reportes.mejores_clientes.pdf.table')

</body>
</html>

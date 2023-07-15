<!DOCTYPE html>
<html>
<head>
  <title>Documento</title>
</head>

<style type="text/css">
	@include('reportes.partials.css.productos_clientes')
</style>
<body>
  
  @include('reportes.partials.pdf.productos_mas_vendidos.header')
  @include('reportes.partials.pdf.productos_mas_vendidos.table')


</body>
</html>

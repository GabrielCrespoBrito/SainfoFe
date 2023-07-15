<html>
<head>
  <title>Documento</title>
  <style>
  <meta http-equiv="content-type" content="text/html; utf-8">

  @include('reportes.partials.css.productos_clientes')
  </style>
</head>
<body>
  @include('reportes.mejores_clientes.pdf.header')
  @include('reportes.mejores_clientes.pdf.table')
</body>
</html>

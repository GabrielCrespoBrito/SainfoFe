<?php
	// $html = ob_get_clean();
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Documento</title>
</head>
<style type="text/css">
  @include('reportes.entidades.pdf.css')
</style>

<body>
  @include('reportes.entidades.pdf.header')
  @include('reportes.entidades.pdf.table')  
</body>
</html>

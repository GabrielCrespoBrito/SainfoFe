<!DOCTYPE html>
<html lang="es">
<style>
</style>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta charset="UTF-8">
	<title></title>
	<style>
  @include('cotizaciones.partials.pdf.style')
</style>

</head>
<body>
	<div class="container" style="font-family: 'Helvetica' !important">		
		@include('ventas.partials.pdf.a4.cabezera')
		@include('ventas.partials.pdf.a4.cliente')		
		<div class="lipo" style="">
			@include('cotizaciones.partials.pdf.items')			
			@include('cotizaciones.partials.pdf.pie')
		</div>		
	</div>
</body>
</html>
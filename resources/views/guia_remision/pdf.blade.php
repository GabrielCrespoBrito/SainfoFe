<!DOCTYPE html>
<html>
<head>
<title>{{ $title }}</title>
<style type="text/css">
  @include('guia_remision.partials.pdf.css')
</style>
</head>
<body>
	<?php $is_boleta = false; ?>
	<div class="container" style="font-family: 'Helvetica' !important ">
    @include('ventas.partials.pdf.a4.cabezera')        
		@include('guia_remision.partials.pdf.cliente')
		@if($hasFormato)
			@include('guia_remision.partials.pdf.direcciones')
		@endif
		@include('guia_remision.partials.pdf.info')
		<div class="lipo" style="">
			@include('guia_remision.partials.pdf.items')
			@if($hasFormato)
			  @include('guia_remision.partials.pdf.pie', ['peso_total' => $guia['guiporp'] ])
			@endif
		</div>
	</div>
</body>
</html>
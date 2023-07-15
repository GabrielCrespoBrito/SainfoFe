<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta charset="UTF-8">
	<title>{{ $title }} </title>
	<link href="{{ asset('css/guia_pdf.css') }}" rel="stylesheet">
</head>
<body>
	<?php $is_boleta = false; ?>
	<div class="container" style="font-family: 'Helvetica' !important ">
    {{-- @include('guia_remision.partials.pdf.cabezera') --}}
    @include('ventas.partials.pdf.a4.cabezera')        
		@include('guia_remision.partials.pdf.cliente')
		@include('guia_remision.partials.pdf.direcciones')
		@include('guia_remision.partials.pdf.info')
		<div class="lipo" style="">
			@include('guia_remision.partials.pdf.items')
		  @include('guia_remision.partials.pdf.pie', ['peso_total' => $guia['guiporp'] ])
		</div>

	</div>
</body>
</html>
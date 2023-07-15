<!DOCTYPE html>
<html>
<head>
	<title>Documento</title>
</head>
<style type="text/css">

	html {
	}

	body {
		font-size: .8em;
		margin: 0;
		/*outline: 2px solid red*/
	}

	.table_items {
		padding: 0;
		margin: 0;
	}


	.table_items {
		border-collapse: collapse;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;		
	}

	.table_items .totales_tr td {
		border-top: 1px solid #999;
		border-bottom: 1px solid #999;
		font-weight: bold;
	}

	.table_items .totales_tr.total_table td {
		color: blue;
		padding-top: 10px;
		padding-bottom: 10px;		
	}


	.table_items .thead td {
		border-top: 1px solid #999;
		border-bottom: 1px solid #999;		
	}


	.documento_tr td {
		/*background-color: #ccc;*/
		font-weight: bold;
		text-align: left;
	}
</style>
<body>

	@include('reportes.partials.pdf.ventas.header')

	@if($tipo_reporte == "venta" )
	  @include('reportes.partials.pdf.ventas.table_venta')  

	@elseif($tipo_reporte = "detalle" )
	  @include('reportes.partials.pdf.ventas.table_detalle')
	@else 
	  @include('reportes.partials.pdf.ventas.table_items') --}}
	@endif


</body>
</html>

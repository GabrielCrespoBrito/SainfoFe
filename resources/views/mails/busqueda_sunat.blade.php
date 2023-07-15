<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">

		.data {
			/*background-color: #ccc;*/
		}

		.data .totales {
			display: inline-block;
			padding: 5px;
			margin-left: 10px;
			border: 1px solid #000;
		}		


		.bt {
			border-top: 1px solid #000;
		}

		.bb {
			border-bottom: 1px solid #000;
		}

		.bl {
			border-left: 1px solid #000;
		}

		.br {
			border-right: 1px solid #000;
		}		

		.data .totales .value {
			font-weight: bold;
		}		

		.data.leyenda .value {
			border: 1px solid black;
		}		

		.data.leyenda .totales {
			/*border: none;*/
		}		

		.table_info table {
			border-collapse: collapse;
			margin-top: 20px;
			margin-bottom: 20px;
		}

		.table_info tbody td {
			border-bottom: 1px solid black;
		}

		.table_info table * {
			text-align: center;
		}

		.table_info table thead tr th  {
			border-bottom: 1px solid #000;
		}

		.table_info table tbody tr:last-child td  {
			border-bottom: 1px solid #000;
		}

		.estados {
			padding: 2px 8px;
			margin: 0 4px;

		}

		.estado-ok {
			background-color: #b0feb0b5;;
		}

		.estado-noencontrado {
			background-color: #c28181b5;;
		}

		.estado-errorbusqueda {
			background-color: #f3c784b5;
		}

	</style>
</head>
<body>

@php
	$subject = "Reporte de documentos enviados a la sunat";
	$nombre_empresa = $data['empresa']['nombre'];
	$ruc_empresa = $data['empresa']['ruc'];
	$fecha = $data['fecha'];
	$cant_documentos =  $data['cant_documentos'];
	$busqueda_exitosas =  $data['busqueda_exitosas'];
	$encontrados_sunat =  $data['encontrados_sunat'];
@endphp

<h3> {{ $subject }} </h3>

<div class="data">
	<p> <strong> Empresa: </strong> 
		{{ $nombre_empresa }} <strong>({{ $ruc_empresa }})</strong> 
	</p>

	<p> 
		<strong> Fecha reporte: </strong>  {{ $fecha }}
	</p>
</div>

<hr>

<div class="data">
	<p> <strong> Información: </strong> </p>
	
	<span class="totales" > <strong> Documentos totales: </strong> 
		<span class="value"> {{ $cant_documentos }} </span>
	</span>

	<span class="totales" > <strong> Busqueda exitosas: </strong> 
		<span class="value"> {{ $busqueda_exitosas }} </span>
	</span>

	<span class="totales" > <strong> Encontrados en la sunat: </strong> 
		<span class="value"> {{ $encontrados_sunat }} </span>
	</span>
</div>

<div class="data leyenda">
	<p> <strong> Leyenda: </strong> </p>
	
	<span class="totales" > 
		<span class="estados estado-ok"></span>
		<strong> OK </strong> 
	</span>

	<span class="totales"> 
		<span class="estados estado-noencontrado"></span>
		<strong> No enviados / No Encontrados </strong> 
	</span>

	<span class="totales"> 
		<span class="estados estado-errorbusqueda"> </span>
		<strong> Error busqueda </strong> 	
	</span>

</div>

<div class="table_info">
	<table width="100%">
		<thead>
			<tr>
				<th class="bl bt" colspan="8">Documento</th>
				<th class="bt bl br" colspan="4">Sunat</th>			
			</tr>
			<tr>
				<th class="bl">#</th>
				<th>T.D</th>
				<th>Serie</th>
				<th>Numero</th>
				<th>Fecha</th>
				<th>Estado</th>
				<th>Rpta</th>
				<th>Ticket</th>				
				<th class="bl">Busqueda Sunat</th>
				<th>Encontrado</th>
				<th>Descripción</th>
				<th class="br">Codigo</th>			
			</tr>
		</thead>
		<tbody>
			@foreach( $data['documentos'] as $documento )

				@php
					$busquedaOk = $documento['busqueda_exitosa'];
					$encontrado = $documento['encontrado_sunat'];
					$descripcion = $documento['data_sunat']['description'];
					$rpta = $documento['data_sunat']['rpta'];
					$statusCode = $documento['data_sunat']['statusCode'];
					$statusMessage = $documento['data_sunat']['statusMessage'];					
					$ticket = $documento['TidCodi'] == "03" ? $documento['fe_obse'] : '-' ;

					$classEstado = "";

					if( $documento['buscado_sunat'] == false || $documento['encontrado_sunat'] == false ){
						$classEstado = "estado-noencontrado";
					}

					elseif( $documento['busqueda_exitosa'] == false && $documento['fe_rpta'] ){
						$classEstado = "estado-errorbusqueda";
					}


					elseif( $documento['data_sunat']['rpta']  == "0" ){
						$classEstado = "estado-ok";
					}

					$busqueda_sunat = $documento['buscado_sunat'];
					$encontrado = $documento['encontrado_sunat'];
					$descripcion = $documento['data_sunat']['description'];
					$codigo = $documento['data_sunat']['rpta'];
					if( $documento['data_sunat']['rpta'] == "9" ){
					}

				@endphp

			<tr class="{{ $classEstado }}">
				<td class="bl"> {{ $documento['VtaOper'] }} </td>
				<td> {{ $documento['TidCodi'] }} </td>				
				<td> {{ $documento['VtaSeri'] }} </td>
				<td> {{ $documento['VtaNumee'] }} </td>
				<td> {{ $documento['VtaFvta'] }} </td>
				<td> {{ $documento['VtaEsta'] }} </td>
				<td> {{ $documento['fe_rpta'] }} </td>
				<td> {{ $ticket }} </td>				
				<td class="bl"> {{ $busqueda_sunat ? "Si" : "No" }} </td>
				<td> {{ $encontrado ? "Si" : "No" }} </td>
				<td> {{ $descripcion ? $descripcion  : '-' }}  </td>
				<td> {{ $codigo }}  </td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

@include('mails.partials.pie')

</body>
</html>

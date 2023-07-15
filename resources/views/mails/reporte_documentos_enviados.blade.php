
<p> {{ $subject }}</p>

<div class="info">{{ $mensaje }} </div>
<div class="info"><strong>  Fecha de envio: {{ $fecha }} </strong></div>

<div class="empresa_documentos">
	@foreach( $empresas as $empresa)
		@if( $empresa['send_email'] )		

			<div class="table" style="border-top: 2px solid #ccc; padding: 20px 0">	
				<h2> {{ $empresa['empresa_name'] }} {{ $empresa['empresa_ruc']  }} </h2>
				@if( $is_resumen )
					<div class="error">
						<p> Error en <strong> {{ $empresa['error']['tarea'] }} </strong> </p>
						<p> El error es <strong> {{ $empresa['error']['message'] }} </strong> </p>
					</div>
				@endif

				<table class="table_documentos" width="100%">
					<tr class="thead">
						<td style="text-align: left"> VtaOper </td>
						<td style="text-align: left"> VtaSeri </td>
						<td style="text-align: left"> VtaNumee </td>
						<td style="text-align: left"> VtaFvta </td>		
						@if(!$is_resumen)				
							<td style="text-align: left"> Error Cod </td>
							<td style="text-align: left"> Error Desc </td>
						@endif
					</tr>
					@foreach( $empresa['documentos'] as $documento  )
					<tr class="tbody">
						<td> {{ $documento['VtaOper'] }} </td>
						<td> {{ $documento['VtaSeri'] }} </td>
						<td> {{ $documento['VtaNumee'] }} </td>
						<td> {{ $documento['VtaFvta'] }} </td>						
						@if(!$is_resumen)				
							<td> {{ $documento['Error_code'] }} </td>
							<td> {{ $documento['Error_message'] }} </td>			
						@endif
					</tr>
					@endforeach
				</table>
			</div>
		@endif
	@endforeach
</div>

@include('mails.partials.pie')
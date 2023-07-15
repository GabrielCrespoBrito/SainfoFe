
<p> {{ $subject }}</p>

<div class="info">{{ $mensaje }} </div>
<div class="info"><strong>  Fecha de envio: {{ $fecha }} </strong></div>

<div class="empresa_documentos">
	@foreach( $empresas as $empresa)
		@if( $empresa['send_email'] )		

			<div class="table" style="border-top: 2px solid #ccc; padding: 20px 0">	
				<h2> {{ $empresa['empresa_name'] }} {{ $empresa['empresa_ruc']  }} </h2>

				<table class="table_documentos" width="100%">
					<tr class="thead">
						<td style="text-align: left"> GuiOper </td>
						<td style="text-align: left"> GuiSeri </td>
						<td style="text-align: left"> GuiNumee </td>
						<td style="text-align: left"> GuiFemi </td>	
						<td style="text-align: left"> Codigo error </td>		
						<td style="text-align: left"> Descripcion error </td>		

					</tr>
					@foreach( $empresa['documentos'] as $documento  )
					<tr class="tbody">
						<td> {{ $documento['GuiOper'] }} </td>
						<td> {{ $documento['GuiSeri'] }} </td>
						<td> {{ $documento['GuiNumee'] }} </td>
						<td> {{ $documento['GuiFemi'] }} </td>
						<td> {{ $documento['Error_code'] }} </td>
						<td> {{ $documento['Error_message'] }} </td>

					</tr>
					@endforeach
				</table>
			</div>
		@endif
	@endforeach
</div>

@include('mails.partials.pie')
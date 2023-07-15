<p> Estimado Cliente, </p>

<div class="info"> 
	@if( $data['cotizacion']->isOrder() )
		Se adjunta en este email:  
		<p> 
			<strong> ORDEN DE PAGO N°<span style="font-weight: bold"></span> {{ $data["cotizacion"]->CotNume }} 
			</strong>
		</p>

		<p>
			{{ $data['cotizacion']->cotobse }}
		</p>


	@else

	Le hacemos presente la cotización <span style="font-weight: bold"></span> {{ $data["cotizacion"]->CotNume }} 

	@endif

</div>

<div class="table">

	<table style="margin-top:20px" style="color: #999999; background-color: #fafafa; border-left: 2px solid gray">
		<tr>
			<td> {{ $data['mensaje'] }}</td>
		</tr>
	</table>
	@if( $data['cotizacion']->isOrder() )	
	@else
		@include('mails.partials.info_factura_cotizacion')
	@endif

</div>



@if( $data['cotizacion']->isOrder() )	
	<p>  Saludos, </p>
	<p> Equipo de CORPORACION SAINFO E.I.R.L </p>

@else 
	@include('mails.partials.pie')
@endif

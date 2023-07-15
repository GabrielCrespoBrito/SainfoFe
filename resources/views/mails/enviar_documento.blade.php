<p> Estimado Cliente, </p>
<div class="cliente"> 
	<p class="nombre">Sr(es). <span style="background-color:#fafafa ">{{ $data['cliente_nombre'] }} </span> </p>
	<p class="ruc">RUC <span style="font-weight: bold" >{{ $data['cliente_ruc'] }}</span>  </p>
</div>

<div class="info">Informamos a usted que el documento <span style="font-weight: bold"> {{ $data["cliente_documento"] }}</span>, ya se encuentra disponible</div>

<div class="table">

	@include('mails.partials.info_factura')

	@if( isset($data['mensaje']) )
	<table>
		<tr>
			<td> $data['mensaje'] </td>
		</tr>
	</table>
	@endif


</div>

@include('mails.partials.pie')

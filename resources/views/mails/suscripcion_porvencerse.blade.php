<p> Estimado Cliente, </p>

<div class="info">Informamos a usted que el documento <span style="font-weight: bold"> xxxx</span>, ya se encuentra disponible</div>

<div class="table">

	{{-- @include('mails.partials.info_factura') --}}
	{{-- @if( strlen($data['mensaje']) )
	<table style="margin-top:20px" style="color: #999999; background-color: #fafafa; border-left: 2px solid gray">
		<tr>
			<td> {{ $data['mensaje'] }}</td>
		</tr>
	</table>
	@endif --}}


</div>

@include('mails.partials.pie')

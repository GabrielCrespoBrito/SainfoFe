<table width="100%" class="table-items oneline table-inline-report" border="0" cellspacing="0" cellpadding="0">
	<thead>    
    <tr class="header">
			<td class="text-left"> Fecha </td>
			<td class="text-left"> TD </td>
			<td class="text-left" > Serie </td>
			<td class="text-left"> Número </td>
			<td class="text-left"> RUC </td>
			<td class="text-left" style="width:25%"> Razon social</td>
			<td class="text-left"> Moneda </td>
			<td class="text-right"> Importe</td>
			<td class="text-right">Cod Detr. </td>
			<td class="text-right">% Detr. </td>
			<td class="text-right"> Import Detr. </td>
		</tr>	
	</thead>

	{{-- Tbody --}}

	<tbody>

		@foreach( $data_report['ventas'] as $venta )
			<tr> 
				<td class="text-left">{{ $venta->VtaFvta }} </td> 
				<td class="text-left">{{ $venta->TidCodi }} </td> 
				<td class="text-left">{{ $venta->VtaSeri }} </td> 
				<td class="text-left">{{ $venta->VtaNumee }} </td> 
				<td  class="text-left">{{ $venta->PCRucc }} </td> 
				<td  class="text-left" title="{{ $venta->PCNomb }}">{{ $data_report['isPDF'] ? $venta->PCNomb :  substr_custom($venta->PCNomb,30,'...') }} </td> 
				<td  class="text-left">{{ $venta->monabre }} </td> 
				<td class="text-right">{{ $venta->VtaImpo }} </td> 
				<td class="text-right">{{ $venta->VtaDetrCode }} </td> 
				<td class="text-right">{{ $venta->VtaDetrPorc }} </td> 
				
				<td class="text-right">
					{{ $venta->VtaDetrTota != "0" ? $venta->VtaDetrTota : (($venta->VtaImpo/100) * $venta->VtaDetrPorc) }} 
				</td>

			</tr>
		@endforeach					
	</tbody>
</table>
@php
	$codigo = $code;
	$descripcion = $items->first()->DetNomb;
	$tipo = $items->first()->tiecodi;	
	$unidad = $items->first()->unpcodi;	
@endphp

<tr class="tr_producto">
	<td colspan="14" class="td_info border-left border-right">
		<div> 
			<span class="propiedad"> CODIGO DE EXISTENCIA: </span> 
			<span class="value"> {{ $codigo }} </span>
		</div>
		<div> 
			<span class="propiedad"> DESCRIPCION: </span> 
			<span class="value"> {{ $descripcion }} </span>		
		</div>
		
		<div> 
			<span class="propiedad"> TIPO: </span> 
			<span class="value"> {{ $tipo }} </span>		
		</div>	

		<div> 
			<span class="propiedad"> CODIGO DE UNID. MEDIDA:</span> 
			<span class="value"> {{ $unidad }} </span>
		</div>
	</td>
</tr>
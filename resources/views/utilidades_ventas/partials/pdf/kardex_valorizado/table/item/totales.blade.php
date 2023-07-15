{{-- Totales  --}}
<tr class="tr_descripcion total">
	<td class="border-left"> Totales  </td>
	<td></td>		
	<td></td>
	<td></td>
	<td class="border-right"> </td>

	<td>{{ fixedValueCustom($info_total['entrada']['cantidad']) }}</td>
	<td></td>	
	<td class="border-right"> {{ fixedValueCustom($info_total['entrada']['costo_total']) }}</td>


	<td> {{ fixedValueCustom($info_total['salida']['cantidad']) }}</td>
	<td></td>	
	<td class="border-right"> {{ fixedValueCustom($info_total['salida']['costo_total']) }}</td>


	<td> {{ fixedValueCustom( $info_total['entrada']['cantidad'] - ($info_total['salida']['cantidad'])) }} </td>	
	<td></td>
	<td class="border-right"></td>		
</tr>

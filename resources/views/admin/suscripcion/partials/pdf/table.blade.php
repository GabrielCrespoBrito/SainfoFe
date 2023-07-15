<table class="table_factura table_orden">
  
  <thead>
		<tr>
			<td>Codigo</td>
			<td class="text-c"">Descripción</td>
			<td class="text-r">Cantidad</td>
			<td class="text-r">Precio</td>
			<td class="text-r">IGV</td>
			<td class="text-r">Dcto</td>
			<td class="text-r">Total</td>				
		</tr>
	</thead>
	
  <tbody>
		<tr>
			<td>{{ $codigo }}</td>				
			<td class="text-c">{{ $nombre }} </td>				
			<td class="text-r">{{ $cantidad }}</td>
			<td class="text-r">{{ $precio }}</td>
			<td class="text-r">{{ $igv }}</td>
			<td class="text-r">{{ $descuento }}</td>
			<td class="text-r">{{ $total }}</td>
		</tr>
	</tbody>

</table>
<table width="100%" class="table_items">
	<thead>
		<tr>
			<th class="text-center"> # </th>			
			<th class="text-center"> Codigo </th>
			<th class="text-center"> Unidad </th>
			<th class="text-left"> Descripcion </th>
			<th class="text-center"> Marca </th>
			<th> Cantidad </th>			
		</tr>
	</thead>
	<tbody>
		@if( $productos )
		@foreach( $productos as $producto )
			<tr>
				<td class="text-center"> {{ $loop->index +1 }}</td>
				<td class="text-center"> {{ $producto['ProCodi'] }}  </td>
				<td class="text-center"> {{ $producto['UniCodi'] }} </td>
				<td class="text-left"> {{ $producto['ProNomb'] }}  </td> 
				<td class="text-center"> {{ $producto['MarNomb'] }}  </td>
				<td class="text-r"> {{ $producto['Cantidad'] }}    </td>
			</tr>
		@endforeach
		@else 
			<tr>
				<td colspan="5" style="text-align: center";> No ay registros en estas fechas </td>
			</tr>
		@endif
	</tbody>
</table>

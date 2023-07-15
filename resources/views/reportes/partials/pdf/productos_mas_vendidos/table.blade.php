<div>
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
				<td class="text-center"> {{ $producto['codigo_producto'] }} </td>
				<td class="text-center"> {{ $producto['unidad'] }} </td>
				<td class="text-left"> {{ $producto['nombre'] }} </td>
				<td class="text-center"> {{ $producto['marca'] }} </td>
				<td class="text-r"> {{ decimal($producto['cantidad']) }} </td>
			</tr>
		@endforeach
		@else 
			<tr>
				<td colspan="6" style="text-align: center";> No hay registros en estas fechas </td>
			</tr>
		@endif
	</tbody>
</table>
</div>
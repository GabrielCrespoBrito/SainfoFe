<table width="100%" class="table_items">
	<thead>
		<tr>
			<th class="text-center"> # </th>			
			<th class="text-center"> Codigo </th>
			<th class="text-left" style="padding-left:10px; padding-right:10px"> Ruc </th>
			<th class="text-left"> Raz&oacute;n Social </th>
			<th class="text-right" style="padding-right:15px"> Cantidad Ventas </th>

			<th class="text-right"> Importe </th>			
		</tr>
	</thead>
	<tbody>
		@if( $clientes )
		@foreach( $clientes as $cliente )
			<tr>
				<td class="text-center"> {{ $loop->index +1 }}</td>
				<td class="text-center"> {{ $cliente['codigo'] }}  </td>
				<td class="text-left" style="padding-left:10px; padding-right:10px"> {{ $cliente['documento'] }} </td>
				<td class="text-left"> {{ $cliente['nombre'] }}  </td> 
				<td class="text-right" style="padding-right:15px"> {{ decimal($cliente['cantidad']) }} </td>
				<td class="text-right"> {{ decimal($cliente['importe'],2) }}    </td>
			</tr>
		@endforeach
		@else 
			<tr>
				<td colspan="6" style="text-align: center";> No hay registros en estas fechas </td>
			</tr>
		@endif
	</tbody>
</table>

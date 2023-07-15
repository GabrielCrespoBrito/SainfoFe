<table width="100%" class="table_items">
	<thead>
		<tr>
			<th class="text-center"> # </th>			
			<th class="text-center"> Codigo </th>
			<th class="text-center"> Ruc </th>
			<th class="text-center"> Razón Social </th>
			<th class="text-right"> Cantidad Ventas </th>
			<th class="text-right"> Importe </th>			
		</tr>
	</thead>
	<tbody>
		@if( $clientes )
		@foreach( $clientes as $cliente )
			<tr>
				<td class="text-center"> {{ $loop->index +1 }}</td>
				<td class="text-center"> {{ $cliente['Codigo'] }}  </td>
				<td class="text-center"> {{ $cliente['Ruc'] }} </td>
				<td class="text-left"> {{ $cliente['Razon_Social'] }}  </td> 
				<td class="text-right"> {{ $cliente['Cantidad'] }}  </td>
				<td> {{ $cliente['Importe'] }}    </td>
			</tr>
		@endforeach
		@else 
			<tr>
				<td colspan="6" style="text-align: center";> No ay registros en estas fechas </td>
			</tr>
		@endif
	</tbody>
</table>

<table width="100%" style="page-break-inside: auto;" class="table_items">
	
	<thead>
		<tr>
			<th width="4%" class="text-left"># </th>
			<th width="40%" class="text-left"> NOMBRE </th>
			<th width="10%" class="text-left"> RUC </th>
			<th class="text-right"> PEDIDOS </th>
			<th class="text-right"> ACUENTA </th>
			<th class="text-right"> SALDO </th>
			<th class="text-right"> COBRANZAS </th>
			<th class="text-right"> ACUENTA </th>			
		</tr>
	</thead>
	
	<tbody>
		@foreach( $clientes as $cliente  )
			<tr>
				<td class="text-left"> {{ $loop->index+1 }} </strong>	</td>
				<td class="text-left"> {{ $cliente['nombre'] }} </strong>	</td>
				<td class="text-left"> {{ $cliente['ruc'] }} </strong>	</td>				
				<td class="text-right">{{ $cliente['pedidos'] }}</td>
				<td class="text-center"></td>
				<td class="text-right">{{ $cliente['saldo'] }}</td>
				<td class="text-right">{{ $cliente['cobranza'] }}</td>
				<td class="text-right"></td>
			</tr>
		@endforeach
	</tbody>

	<tfoot>
			<tr>
				<td class="text-left" colspan="2"> <strong> Totales: </strong>	</td>
				<td class="text-left"></td>				
				<td class="text-right total"> <strong> {{ fixedValue($totales['pedido']) }} </strong> </td>
				<td class="text-center"></td>
				<td class="text-right total"> <strong> {{ fixedValue($totales['saldo']) }} </strong> </td>
				<td class="text-right total"> <strong> {{ fixedValue($totales['cobranza']) }} </strong> </td>
				<td class="text-right"></td>
			</tr>
	</tfoot>
</table>

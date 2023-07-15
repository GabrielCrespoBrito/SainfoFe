<table class="table_factura border" height="80%">
	<thead>
		<tr>
			<td>CODIGO</td>
			<td>CANTIDAD</td>
			<td>UNIDAD</td>
			<td width="50%">DESCRIPCIÓN</td>
			<td>PESO</td>
		</tr>
	</thead>
	<tbody>
		@foreach( $items as $item )
		<tr>
			<td>{{ $item->DetCodi }}</td>
			<td>{{ $item->Detcant }}</td>				
			<td>{{ $item->DetUnid }}</td>				
			<td style="text-align: left;"> {{ $item->DetNomb }} {{ $item->DetDeta }} </td>
			<td>{{ $item->DetPeso }}</td>
		</tr>
		@endforeach			
	</tbody>
</table>

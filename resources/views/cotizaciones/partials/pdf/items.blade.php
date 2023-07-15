


<table class="table_factura" height="80%">
	<thead>
		<tr>
			<td>ITEM</td>
			<td>CODIGO</td>
			<td width="300px">DESCRIPCIÓN</td>
			<td>UNID</td>

			@if($showPeso)
			<td class="text-r">P/Kgs</td>			
			@endif


			<td class="text-r">CANT</td>

			@if($orden_campos['valor_unitario']  )
			<td style="text-align: right">V. UNIT</td>
			@endif

			@if($orden_campos['precio_unitario']  )
			<td style="text-align: right">P. UNIT</td>
			@endif

			@if($orden_campos['importe']  )
			<td style="text-align: right; padding-right:5px">IMPORTE</td>				
			@endif

			{{-- <td class="text-r">V.UNIT</td>
			<td class="text-r">P.UNIT</td>
			<td class="text-r">IMPORTE</td>				 --}}
		</tr>
	</thead>
	<tbody>
		@foreach( $items as $item )
		<tr>
			<td>{{ $item->DetItem }}</td>
			<td>{{ $item->DetCodi }}</td>				
			<td style="text-align: left;">{{ $item->DetNomb }}
				@if( $item->DetDeta )
				<br> <span class="font-style:italic"></span> {!! $item->DetDetaFormat() !!}
				@endif
			</td>				
			<td>{{ $item->DetUnid }}</td>

			@if($showPeso)
				<td class="text-r">{{ fixedValue($item->DetPeso) }}</td>
			@endif
			<td class="text-r">{{ $item->DetCant }}</td>


			@if($orden_campos['valor_unitario']  )
			<td class="text-r"> {{ decimal($item->valorUnitario(), $decimals ) }}</td>
			@endif

			@if($orden_campos['precio_unitario']  )
			<td class="text-r"> {{ decimal($item->precioUnitario(), $decimals ) }}</td>
			@endif

			@if($orden_campos['importe']  )
			<td class="text-r"> {{ decimal( $item->DetImpo,  $decimals ) }}</td>
			@endif

		</tr>
		@endforeach			
	</tbody>
</table>





<div class="aa">
<table class="table_factura">
	<thead>
		<tr style="text-align: left">
			<td style="text-align: left; padding-left:5px">ITEM</td>
			<td style="text-align: left">CODIGO</td>
			<td style="text-align: left">UNID</td>
			<td style="text-align: left" width="250px">DESCRIPCIÓN</td>
			<td style="text-align: right">CANT.</td>
			
			@if($orden_campos['valor_unitario']  )
			<td style="text-align: right">V. UNIT</td>
			@endif

			@if($orden_campos['precio_unitario']  )
			<td style="text-align: right">P. UNIT</td>
			@endif

			@if($orden_campos['descuento']  )
			<td style="text-align: right">DCTO</td>				
			@endif

			@if($orden_campos['importe']  )
			<td style="text-align: right; padding-right:5px">IMPORTE</td>				
			@endif

		</tr>
	</thead>
	<tbody>		
		@php
		@endphp
		@foreach( $items as $item )
			<tr valign="top" style="border-bottom: 1px solid #000000">
				<td style="text-align: left;padding-left:5px;">{{ $item->DetItem }}</td>
				<td style="text-align: left">{{ $item->DetCodi }}</td>
				<td style="text-align: left">{{ $item->DetUnid }}</td>
				<td style="text-align: left;">{{ $item->DetNomb }}<br><span style="font-style:italic">{!! $item->DetDetaFormat() !!}</span></td>
				<td class="text-right">{{ $item->DetCant }}</td>
				@if($orden_campos['valor_unitario']  )
				<td class="text-right">{{ decimal($item->precioUnitario(), $decimals ) }}</td>
				@endif
				@if($orden_campos['precio_unitario']  )
				<td class="text-right">{{ decimal($item->valorUnitario(), $decimals ) }}</td>
				@endif
				@if($orden_campos['descuento']  )
				<td class="text-right">{{ fixedValue($item->DetDctoV, $decimals) }}</td>
				@endif
				@if($orden_campos['importe']  )
				<td class="text-right" style="padding-right:5px">{{ fixedValue($item->DetImpo,$decimals) }} </td>
				@endif
			</tr>
		@endforeach			
	</tbody>
</table>
	@include('ventas.partials.pdf.a4.pie')
</div>
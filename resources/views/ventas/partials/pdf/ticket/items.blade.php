{{-- @dd( $orden_campos ) --}}
@php
	 
	$cant = 0; 

	if($orden_campos['valor_unitario']){
		$cant++;
	}
	if($orden_campos['precio_unitario']){
		$cant++;
	}
	if($orden_campos['descuento']){
		$cant++;
	}
	if($orden_campos['importe']){
		$cant++;
	}

	$cant = $cant ? $cant : 1;
	$width = (int) (80 / $cant) . '%';

@endphp

<!-- items -->
<div class="items">
		
	<div class="descripcion">
		<span>Unid</span> <span>Descripción</span>
	</div>
  
	<div class="item">			
		<table style="width: 100%">
			<tr class="right">
				<td style="width:20%;">Cant.</td>

				@if($orden_campos['valor_unitario']  )
				<td style="width:{{ $width }}; text-align: right">V. Unit</td>
				@endif

				@if($orden_campos['precio_unitario']  )
				<td style="width:{{ $width }}; text-align: right">P. Unit</td>
				@endif

				@if($orden_campos['descuento']  )
				<td style="width:{{ $width }}; text-align: right">Dcto.</td>				
				@endif

				@if($orden_campos['importe']  )
				<td style="width:{{ $width }}; text-align: right;">Importe</td>				
				@endif

			</tr>			
		</table>
	</div>

@foreach( $items as $item )

<p class="border-separador"></p>
	
	<span>{{ $item->DetUnid }}</span> <span>{{ $item->DetNomb }}</span>
	<div class="item">
		<table style="width: 100%">
			<tr class="right">
				<td style="width:20%; text-right">{{ $item->DetCant }}</td>

				@if($orden_campos['valor_unitario']  )
				<td class="width:{{ $width }}; text-right">{{ decimal($item->precioUnitario(), $decimals ) }}</td>				
				@endif

				@if($orden_campos['precio_unitario']  )
				<td class="width:{{ $width }}; text-right">{{ decimal($item->valorUnitario(), $decimals ) }}</td>	
				@endif

				@if($orden_campos['descuento']  )
				<td style="width:{{ $width }}; text-right">{{ $item->DetDcto }}</td>
				@endif

				@if($orden_campos['importe']  )
				<td style="width:{{ $width }}; text-right">{{ $item->DetImpo }}</td>					
				@endif

			</tr>			
		</table>
	</div>

@endforeach

</div> 
<!-- /items -->
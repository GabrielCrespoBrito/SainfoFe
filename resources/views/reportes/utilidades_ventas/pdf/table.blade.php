<table width="100%" style="page-break-inside: auto;" class="table_items">
	<thead>
		<tr>
			<th class="text-center"> Fecha </th>			
			{{-- @dd($is_detallado) --}}
			@if( $is_detallado )	
				<th class="text-center"> N° Doc </th>
			@endif

			<th class="text-right"> Ventas </th>
			<th class="text-right"> Costo </th>
			<th class="text-right"> Utilidad </th>

			@if( $is_detallado )
				<th width="30%" class="text-left" style="text-indent: 15px"> Raz&oacute;n social </th>			
			@endif
		</tr>
	</thead>

	<tbody>

		@php
			$total_venta = 0;
			$total_costo = 0;
			$total_utilidad = 0;			
		@endphp


		{{-- Detallada --}}
		@if( $is_detallado )

			@foreach( $data as $VtaNume => $items )

			@php
				$total_venta += $venta_col = $items->sum('DetImpo');

				$costo = 0;
				foreach( $items as $item ){
					$total_costo += $costo += ($item->ProPUCS  * $item->DetCant );
				}

				$total_utilidad += $utilidad = ($venta_col - $costo);

			@endphp
				<tr>
					<td class="text-center"> {{ $items->first()->VtaFvta }} </td>			
					<td class="text-center"> {{ $VtaNume }} </td>
					<td class="text-right total"> {{ fixedValue($venta_col) }} </td>
					<td class="text-right total"> {{ fixedValue($costo) }} </td>
					<td class="text-right total"> {{ fixedValue($utilidad) }}   </td>
					<td class="text-left" style="text-indent: 15px">  {{ $items->first()->PCNomb }} </td>			
				</tr>
			@endforeach
		{{-- Detallada --}}

		{{-- Resumen --}}
		@else

				{{-- Ventas del dia --}}
				@foreach( $data as $day => $ventas )

					@php

						$venta_col = 0;
						$costo = 0;
						$utilidad  = 0;

						$ventas_group = $ventas->groupBy('VtaNume');

						foreach( $ventas_group as $VtaNume => $items ){						
							
							$venta_col += $items->sum('DetImpo');

							foreach( $items as $item ){								
								$costo += ($item->ProPUCS * $item->DetCant);
							}
						}

						$total_costo += $costo;
						$total_venta += $venta_col;
						$total_utilidad += $utilidad = ($venta_col - $costo);

					@endphp

					<tr>
						<td class="text-center"> {{ $day }} </td>			
						<td class="text-right total"> {{ fixedValue($venta_col) }} </td>
						<td class="text-right total"> {{ fixedValue($costo) }} </td>
						<td class="text-right total"> {{ fixedValue($utilidad) }}   </td>
					</tr>
				@endforeach

		@endif

	</tbody>


	<tfoot>

		<tr>
			<th class="text-center"></th>			
			{{-- @dd($is_detallado) --}}
			@if( $is_detallado )	
				<th class="text-center"></th>
			@endif

			<th class="text-right total"> {{ fixedValue($total_venta) }} </th>
			<th class="text-right total"> {{ fixedValue($total_costo) }} </th>
			<th class="text-right total"> {{ fixedValue($total_utilidad) }} </th>

			@if( $is_detallado )
				<th class="text-right"></th>			
			@endif
		</tr>
		
	</tfoot>	


</table>

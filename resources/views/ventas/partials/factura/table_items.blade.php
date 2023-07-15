
<div class="row">
  <div class="col-md-12" style="overflow-x: scroll; {{ !$create ? 'margint-top:20px' : '' }}">

	<table id="table-items" class="table sainfo-table oneline">
	<thead>
	  <tr>
			<td width="30px"> # </td>        
			<td width="30px"> B/S </td>
			<td> Base </td>
			<td> Código </td>    
			<td> Unidad </td>    
			<td width="300px"> Descripción </td>    
			<td class="td-number"> Cantidad </td>
			<td class="td-number"> Precio </td>
			<td class="td-number"d> Dcto %</td>
			<td class="td-number"> IGV </td>    
			<td class="td-number"> Importe </td>
			@if($create)
			<td class="td-number"> &nbsp; </td>
			@endif
		</tr>
	</thead>
	<tbody> 
		@if( ! $create )
			@foreach( $venta->items as $item ) 
			<tr>
				<td style="text-align:left" class="text-left">{{ $item->DetItem }} </td>        
				<td style="text-align:left" class="text-left TieCodi">{{ optional($item->producto)->tiecodi }} </td>
				<td style="text-align:left" class="text-left DetBase">{{ $item->DetBase }} </td>
				<td style="text-align:left" class="text-left UniCodi">{{ $item->DetCodi }}</td>
        @php
          $unidad_name = optional($item->unidad)->withListaName();
        @endphp
				<td style="text-align:left" class="text-left DetUnid" title="{{ $unidad_name }}"> {{ $unidad_name }} </td>    
				
        <td style="text-align:left" class="text-left DetNomb">
					{{ $item->DetNomb }} 
					@if( $item->DetDeta )
						<br> 
						{!! nl2br(e($item->DetDeta)) !!}
					@endif
				</td>

				<td class="td-number DetCant">{{ $item->DetCant }} </td>
				<td class="td-number DetPrec">{{ $item->DetPrec }} </td>
				<td class="td-number DetDcto">{{ $item->DetDcto }} </td>				
				<td class="td-number DetIGVP">{{ $item->DetIGVP }} </td>    
				<td class="td-number DetImpo">{{ $item->DetImpo }} </td>            
			</tr>				
			@endforeach 
		@endif
	</tbody>
	</table>

  </div>
</div>

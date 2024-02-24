<div class="row">
  <div class="col-md-12" style="overflow-x: scroll; {{ !$create ? 'margint-top:20px' : '' }}">
	<table id="table-items" class="table sainfo-table text-center oneline">
	<thead>
	  <tr>
			<td width="30px"> # </td>        
			<td width="30px"> B/S </td>
			<td> Base </td>
			<td> Código </td>    
			<td> Unidad </td>    
			<td width="200px"> Descripción </td>    
			{{-- <td> Marca </td>     --}}
			<td> Cantidad </td>
			<td> Precio </td>
			<td> Dcto %</td>
			<td> IGV </td>    
			{{-- <td> ISC </td> --}}
			<td> Importe </td>
			@if($create)
			<td> &nbsp; </td>
			@endif
		</tr>
	</thead>
	<tbody> 
		@dd( $is_orden )

		@if( ! $create )
		{{-- Si es una orden de la tienda --}}
			@if( $is_orden)
			<tr class="text-left">
				<td class="text-left">1  </td>        
				<td class="text-left TieCodi"> 1 </td>
				<td class="text-left DetBase"> 1 </td>
				<td class="text-left UniCodi"> 1 </td>  
				<td class="text-left DetUnid" title="""> Unidad  </td>    
				<td class="text-left DetNomb"> Nombre </td>    
				<td class="text-left DetCant"> Cantidad </td>
				<td class="text-left DetPrec"> Precio </td>
				<td class="text-left DetDcto"> 0 </td>				
				<td class="text-left DetIGVP"> 18 </td>    
				<td class="text-left DetImpo"> DetNomb </td>            
			</tr>	
			@endif

		@else
			@foreach( $venta->items as $item ) 
			<tr class="text-left">
				<td class="text-left">{{ $item->DetItem }} </td>        
				<td class="text-left TieCodi">{{ optional($item->producto)->tiecodi }} </td>
				<td class="text-left DetBase">{{ $item->DetBase }} </td>
				<td class="text-left UniCodi">{{ $item->UniCodi }} </td>  
				<td class="text-left DetUnid" title="{{ optional($item->unidad)->withListaName() }}">{{ optional($item->unidad)->withListaName() }} </td>    
				<td class="text-left DetNomb">{{ $item->DetNomb }} </td>    
				<td class="text-left DetCant">{{ $item->DetCant }} </td>
				<td class="text-left DetPrec">{{ $item->DetPrec }} </td>
				<td class="text-left DetDcto">{{ $item->DetDcto }} </td>				
				<td class="text-left DetIGVP">{{ $item->DetIGVP }} </td>    
				<td class="text-left DetImpo">{{ $item->DetImpo }} </td>            
			</tr>				
			@endforeach 
		@endif
	</tbody>
	</table>
  </div>
</div>
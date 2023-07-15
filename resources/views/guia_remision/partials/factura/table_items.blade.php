<div class="row">
  <div class="col-md-12" style="overflow-x: scroll; {{ !$create ? 'margint-top:20px' : '' }}">

	<table id="table-items" class="table sainfo-table text-center oneline">
	<thead>
	  <tr>
			<td> # </td>        
			<td> Código </td>    
			<td> Unidad </td>    
			<td style="width: 300px"> Descripción </td>    
			<td> Marca </td>    
			<td class="text-right-i"> Cantidad </td>
			<td class="text-right-i"> Precio </td>
			<td class="text-right-i"> Importe </td>
			@if($create && $importar == false)
			<td class="text-right-i"> &nbsp; </td>
			@endif
					{{-- @if( $estado_edit !== 'closed' ) --}}
		</tr>
	</thead>
	
	<tbody> 
		@if($accion == "create")
			@if( $importar )
			@foreach( $importar->items as $item ) 
			@php
				$item->DetCant = $item->DetSdCa;
			@endphp

			<tr  data-info="{{ json_encode($item) }}" data-unidades="{{ $item->producto->unidades }}">
				<td class="#">{{ $item->DetItem }} </td>        
				<td class="UniCodi">{{ $item->DetCodi }} </td>    
				<td class="DetUnid">{{ $item->DetUnid }} </td>    
				<td class="DetNomb">{{ $item->DetNomb }} </td>    
				<td class="MarNomb">{{ $item->MarNomb }} </td>    
				<td class="DetCant"> 
					<input type="number" max="{{ $item->DetSdCa }}" min="0" style="width:60px"  name="DetCant" value="{{ $item->DetSdCa }}">
				</td>

				<td class="DetPrec">{{ $item->DetPrec }} </td>
				<td class="DetImpo">{{ $item->DetImpo }} </td>

				@if($create && $importar == false)

				<td>
					@if(!$show)
					<a href='#' class='btn modificar_item btn-xs btn-primary'> <span class='fa fa-pencil'></span> </a>
					<a href='#' class='btn eliminar_item btn-xs btn-danger'> <span class='fa fa-trash'></span> </a>
					@endif
				</td>
				@endif

			</tr>				
			@endforeach 
			@endif


		@else
		@if( $info )
			@foreach( $guia->items as $item ) 
			<tr  data-info="{{ json_encode($item) }}" data-unidades="{{ $item->producto->unidades }}">
				<td class="#">{{ $item->DetItem }} </td>        
				<td class="UniCodi">{{ $item->DetCodi }} </td>
				<td class="DetUnid">{{ $item->DetUnid }} </td>    
				<td class="DetNomb">{{ $item->DetNomb }} <br> {{ $item->DetDeta }}</td>    
				<td class="MarNomb">{{ $item->MarNomb }} </td>    
				<td data-campo="DetCant"  class="DetCant">{{ $item->Detcant }} </td>
				<td data-campo="DetPrec"  class="DetPrec">{{ $item->DetPrec }} </td>
				<td data-campo="DetImpo"  class="DetImpo">{{ $item->DetImpo }} </td>
				<td>
					@if( $estado_edit !== 'closed' )
					<a href='#' class='btn modificar_item btn-xs btn-primary'> <span class='fa fa-pencil'></span> </a>
						@if( $estado_edit == 'open')
							<a href='#' class='btn eliminar_item btn-xs btn-danger'> <span class='fa fa-trash'></span> </a>
						@endif
					@endif
				</td>
			</tr>				
			@endforeach 
		@endif

		@endif
	</tbody>


	</table>

  </div>
</div>

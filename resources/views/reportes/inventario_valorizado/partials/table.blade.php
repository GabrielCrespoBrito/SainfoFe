<table width="100%" class="table-items table-inventario oneline table-inline-report" border="0" cellspacing="0" cellpadding="0">
	<thead>    
    <tr class="header">
			<td class="text-left"> Codigo </td>
			<td class="text-center"> Unidad </td>
			<td class="text-left" width="30%" > Articulo </td>
			<td class="text-right"> Marca </td>
			<td class="text-right"> Peso </td>
			<td class="text-right"> Costo </td>
			<td class="text-right"> Stock </td>
			<td class="text-right"> Importe </td>
		</tr>	
	</thead>

	<tbody>

		@foreach( $data_report as $group )
			
			@include('reportes.inventario_valorizado.partials.tr_header_group', ['codigo' => $group['info_group']['codigo'], 'nombre' => $group['info_group']['nombre'] ])
			
			@foreach( $group['products_group'] as $producto )
				@include('reportes.inventario_valorizado.partials.tr_producto_group', ['producto' => $producto])
			@endforeach

			@include('reportes.inventario_valorizado.partials.tr_total_group', ['total' => $group['info_group']['total'] ])
		@endforeach

	</tbody>

	<tbody>
			@include('reportes.inventario_valorizado.partials.tr_total_general', ['total' => $total_general ])
	</tbody>

</table>
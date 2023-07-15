@include('reportes.partials.pdf.kardex_valorizado.table.item.header' )

{{-- @dd($data) --}}
	{{-- Stock inicial --}}
@include('reportes.partials.pdf.kardex_valorizado.table.item.row' , [ 
	'data' => [
		"", '-', 'Stock',	'ini' ,	'16', 
		"" , "" , "" ,
		"" , "" , "" ,
		$data['stock_inicial']['quantity'] , $data['stock_inicial']['cost_unit'], $data['stock_inicial']['total']
]])


{{-- Iterando los items --}}
@foreach( $data['items'] as $item ) 

	@include('reportes.partials.pdf.kardex_valorizado.table.item.row' , [
		'data' => [
				$item['info']['fecha'], $item['info']['tipo_documento'], $item['info']['serie'], $item['info']['numero'] ,	$item['info']['tipo_operacion'], 
				$item['entrada']['quantity'], $item['entrada']['cost_unit'], $item['entrada']['total'],
				$item['salida']['quantity'], $item['salida']['cost_unit'], $item['salida']['total'],
				$item['saldo']['quantity'], $item['saldo']['cost_unit'], $item['saldo']['total'],
		]])

@endforeach
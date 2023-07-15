@component('components.table', [  'class_name' => 'datatable', 'url' => route('cajas.search_movimientos', ['caja_id' => $caja->CajNume, 'id_tipomovimiento' => $tipo_movimiento ]) , 'id' => 'table_movimiento' , 'class_name' => 'datatable' , 'thead' => ["Nro Oper", 'Mov Caj' , "Documento", "Tipo" , 'Descripciòn', "S./" , "USD" , "Acciones"] ])

@slot('body')
@endslot

@endcomponent
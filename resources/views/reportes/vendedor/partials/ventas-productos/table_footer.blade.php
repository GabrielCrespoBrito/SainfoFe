@include('reportes.vendedor.partials.productos.table_total',[ 'nameTotal' => 'GENERAL', 'class_name' => 'font-size-7', 'cantidad' => decimal($total['cantidad']), 'importe' => decimal($total['importe']) ])


@include('reportes.vendedor.partials.ventas.table_total',[ 'nameTotal' => 'TOTAL GENERAL', 'class_name' => 'font-size-7', 'importe' => decimal($total['importe']), 'pago' => decimal($total['pago']), 'saldo' => decimal($total['saldo']) ])


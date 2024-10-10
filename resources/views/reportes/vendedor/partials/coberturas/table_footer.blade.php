@include('reportes.vendedor.partials.coberturas.table_total', [
    'nameTotal' => 'TOTAL GENERAL',
    'codigo' => '',
    'cantidad' => decimal($total['cantidad']),
    'importe' => decimal($total['importe']),
]) 

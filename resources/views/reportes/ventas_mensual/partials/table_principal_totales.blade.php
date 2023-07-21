<tr class="totales-reporte" data-codi="{{ $codi }}" style="display:none" data-show="false">

<td class="value-name text-center" colspan="11"> 

  @include('reportes.ventas_mensual.partials.table_principal_slot_total', ['nombre' => 'Base Imponible de la operación gravada', 'total' => $totales['base'] ])  

  @include('reportes.ventas_mensual.partials.table_principal_slot_total', ['nombre' => 'Descuento de la Base Imponible', 'total' => $totales['dcto'], 'bg'=> true ])  

  @include('reportes.ventas_mensual.partials.table_principal_slot_total', ['nombre' => 'Monto Total del IGV y/o IPM', 'total' => $totales['igv'] ])  

  @include('reportes.ventas_mensual.partials.table_principal_slot_total', ['nombre' => 'Importe Total de la operación exonerada', 'total' => $totales['exonerada'], 'bg'=> true ]) 

  @include('reportes.ventas_mensual.partials.table_principal_slot_total', ['nombre' => 'Importe Total de la operación inafecta', 'total' => $totales['inafecta'] ]) 

  @include('reportes.ventas_mensual.partials.table_principal_slot_total', ['nombre' => 'Importe Total de ISC', 'total' => $totales['isc'],'bg'=> true ])

  @include('reportes.ventas_mensual.partials.table_principal_slot_total', ['nombre' => 'Importe Total del ICBP', 'total' => $totales['icbper'] ]) 

  @include('reportes.ventas_mensual.partials.table_principal_slot_total', ['nombre' => 'Importe Total del CB', 'total' => $totales['total'],'bg'=> true, 'strong' => true ]) 


</td>

</tr>
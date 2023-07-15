@php
    $links = [];

    if( $model->isAperturada() ){
      $links[] = [ 'id' => $model->CajNume , 'class' => 'cerrar' ,'src' => '#' , 'texto' => 'Cerrar'];
    }
    else {
      $links[] = [ 'id' => $model->CajNume , 'class' => 'reaperturar' ,  'src' => '#' , 'texto' => 'Aperturar'];
    }
    if( $model->isLastApertura() ){
      $links[] = [ 'src' => '#' , 'class' => 'eliminar', 'texto' => 'Eliminar'];
    }
  $links[] = [ 'id' => $model->CajNume , 'class' => 'movimientos' , 'src' => route('cajas.movimientos', ['id_caja' => $model->CajNume ]), 'texto' => 'Movimientos']; 
  $links[] = [ 'target'=> '_blank', 'src' => route('cajas.resumen_pdf_detallado',  ['caja_id' => $model->CajNume]) , 'texto' => 'Reporte Movimientos (PDF)' ];
  $links[] = [ 'target'=> '_blank', 'src' => route('cajas.resumen_pdf_detallado',  ['caja_id' => $model->CajNume,  'tipo' => 'excell' ]) , 'texto' => 'Reporte Movimientos (Excell)'];
  $links[] = [ 'target'=> '_blank', 'src' => route('cuenta.reporte_tipo_pago',  [ 'caja_id' => $model->CajNume, 'tipo' => 'pdf']) , 'texto' => 'Reporte Por Tipo Pago (PDF)'];
  $links[] = [ 'target'=> '_blank', 'src' => route('cuenta.reporte_tipo_pago',  [ 'caja_id' => $model->CajNume, 'tipo' => 'excell']) , 'texto' => 'Reporte Por Tipo Pago (Excell)'];
@endphp

@include('partials.column_accion', ['links' => $links ])

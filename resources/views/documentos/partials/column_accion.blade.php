<?php

  $links = [];
  if( $amazon ){
    
    if( $amazon['Estatus'] ) {
      $links[] = ['src'=>'#','texto'=>'Comprobar','class'=>'checkUpload'];
    }

    else {      
      $links[]=['src'=>"#",'texto'=>'Subir faltantes','class'=>'uploadSingle'];
    }

  }

  else {
    $links[] = ['src' =>'#','texto'=>'Subir archivos','class'=>'uploadSingle'];
  }
?>

@include('partials.column_accion', [ 'links' => $links ])
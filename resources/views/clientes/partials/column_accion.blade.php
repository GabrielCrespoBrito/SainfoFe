@php
  $links = [ 
		[ 'src' => '#' , 'texto' => 'Modificar' , 'class' => 'modificar-cliente' ],
		// [ 'src' => '#' , 'texto' => 'Eliminar' , 'class' => 'eliminar_user' ],
    // [ 'src' => '#' , 'texto' => 'Restaurar' , 'class' => 'restaurar_elemento' , 'data-codigo' => $model->PCCodi , 'data-tipo' => $model->TipCodi ],
	];


  if( $model->UDelete == "*" ){
    $links[] = [ 'src' => '#' , 'texto' => 'Restaurar' , 'class' => 'restaurar_elemento' , 'data-codigo' => $model->PCCodi , 'data-tipo' => $model->TipCodi ];
  }
  else {
    $links[] = [ 'src' => '#' , 'texto' => 'Eliminar' , 'class' => 'eliminar_user' ];
  }
@endphp

@include('partials.column_accion', [
	'links' => $links
])
@php
	
	$name = isset($name) ? $name : "mes";
	$mes = $mes ?? null;
	$m = new App\Mes;
	$data = $m->repository()->all()->reverse()->pluck('mesnomb','mescodi');
	$class_adicional = isset($class_adicional) ? $class_adicional : '';
	if( isset($alloption) ){
		$data = $data->toArray();	
	}

@endphp

{!!
  Form::select(
  	$name ,  
  	$data , 
  	$mes , 
  	[ 'class' => "form-control input-sm {$class_adicional}", 'data-reloadtable' => 'table' ])     
!!}
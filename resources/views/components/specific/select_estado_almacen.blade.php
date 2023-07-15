@php
	$size = $size ?? "col-md-2";
	$name = $name ?? "estadoAlmacen";
	$class_adicional = $class_adicional ?? '';
  $data = [ null => '- Seleccionar Est. Almacen -', 'Pe' => 'Pendiente/Faltante',  'SA' => 'Cerrado'];
  $message = 'Estado de Almacen de documento';
@endphp

<div class="{{ $size }}">
{!!
  Form::select(
  	$name ,  
  	$data , 
  	null , 
  	[ 'class' => "form-control input-sm {$class_adicional}", 'data-reloadtable' => 'table' , 'data-toggle' => 'tooltip', 'title' => $message ])     
!!}
</div>


<?php
$id = $id ?? "modalProducto";
$title_modal = $title_modal ?? "Productos";
$nuevo_producto = $nuevo_producto ?? true;
$btn_aceptar = $btn_aceptar ?? true;
$btn_aceptar_text = $btn_aceptar_text ?? 'Aceptar';

//$grupos = $grupos ?? App\Grupo::with('fams')->get();
$grupos = $grupos ?? App\Grupo::all();
$marcas = $marcas ?? App\Marca::all();

//dd($grupos);
$fields_after = $fields_after ?? false;


$fields = $fields ??
  [
    'Codigo',
    'Unidad',
    'Nombre',
    'Marca',
    'Costo($)',
    'Costo(S)',
    'Margen',
    'Prec.Vta',
    'Stock Tot'
  ];

if ($fields_after) {
  array_unshift($fields, $fields_after);
}

if (isset($locales)) {
  foreach ($locales as $local) {
    $data = [
      'class_name' => 'almacenes',
      'attributes' => [
        'data-id' => substr($local->local->LocCodi, -1)
      ],
      'text' => '<span class="td-almacen-title">Almacen</span>' . $local->local->LocNomb
    ];

    array_push($fields, $data);
  }
} else {
  array_push($fields, ['class_name' => 'almacenes', 'text' => '<span class="td-almacen-title">Almacen</span>' . 'Alma#1'], ['class_name' => 'almacenes', 'text' => '<span class="td-almacen-title">Almacen</span>' . 'Alma#2'], ['class_name' => 'almacenes', 'text' => '<span class="td-almacen-title">Almacen</span>' . 'Alma#3'], ['class_name' => 'almacenes', 'text' => '<span class="td-almacen-title">Almacen</span>' . 'Alma#4'], ['class_name' => 'almacenes', 'text' => '<span class="td-almacen-title">Almacen</span>' . 'Alma#5'], ['class_name' => 'almacenes', 'text' => '<span class="td-almacen-title">Almacen</span>' . 'Alma#6'], ['class_name' => 'almacenes', 'text' => '<span class="td-almacen-title">Almacen</span>' . 'Alma#7'], ['class_name' => 'almacenes', 'text' => '<span class="td-almacen-title">Almacen</span>' . 'Alma#8'], ['class_name' => 'almacenes', 'text' => '<span class="td-almacen-title">Almacen</span>' . 'Alma#9']);
}

array_push($fields, 'Properct', 'Peso', 'Base IGV', 'ISC', 'TieCodi');


?>

@component('components.modal', [ 'id' => $id, 'title' => $title_modal ])

@slot('body')

<div class="row botones_div">

  @if($btn_aceptar)
  <div class="col-md-2 overflow-hidden">
    <a class="btn pull-left btn-primary btn-flat btn-accion"> {{ $btn_aceptar_text }}</a>
  </div>
  @endif

  <div class="form-group col-md-4 p-0">
    <div class="form-group">
      <select name="grupo_filter" data-url="{{ route('productos.buscar_grupo') }}" required="required" class="form-control">
        <option data-familias="" value=""> -- SELECCIONAR GRUPO -- </option>
        @foreach( $grupos as $grupo )
        <option data-familias="{{ $loop->first ? $grupo->familias() : '' }}" value="{{ $grupo->GruCodi }}">{{ $grupo->GruNomb }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="form-group col-md-3">
    <div class="form-group">
      <select name="familia_filter" required="required" class="form-control">
        <option data-familias="" value=""> -- SELECCIONAR FAMILIA -- </option>
      </select>
    </div>
  </div>

  <div class="form-group col-md-3">
    <div class="form-group">
      <select name="marca_filter" required="required" class="form-control">
        <option value> -- SELECCIONAR MARCA -- </option>
        @foreach( $marcas as $marca )
          <option value="{{ $marca->MarCodi }}">  {{$marca->MarNomb}}</option>
        @endforeach
      </select>
    </div>
  </div>


  @if($nuevo_producto)
  <div class="col-md-1">
    <a target="_blank" class="btn pull-right btn-primary btn-flat" href="{{ route('productos.index') }}"> <span class="fa fa-plus"> </span> Nuevo </a>
  </div>
  @endif
</div>

@component('components.table', ['thead' => $fields , 'url' => route('productos.consulta') , 'class_name' => 'sainfo table-oneline' , 'container_class' => 'productos_select' , 'attrs' => 'data-selected=true' ])
@endcomponent

@endslot

@endcomponent
@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Lista Precios',
  'titulo_pagina'  => 'Lista Precios', 
  'bread'  => [ ['Reportes'] , ['Lista Precios'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','reportes/listaprecio/script.js']]
])

@push('css')
  <link rel="stylesheet" href="{{ asset('css/reportes/reporte_basico.css') }}" />
@endpush

@slot('contenido')

<form method="POST" action="{{ route('reportes.listaprecio.pdf') }}" class="form-lista">
  
  @csrf
  
  <div class="row">
    <div class="form-group col-md-4">
			<select name="LisCodi"  class="form-control">
        @foreach($listas->groupBy('LocCodi') as $group )
          <optgroup label="LOCAL: {{ $group->first()->local->LocNomb }}">
            @foreach( $group as $lista )
          <option value="{{ $lista->LisCodi }}"> {{ $lista->LisNomb }} </option>
          @endforeach
        </optgroup>
        @endforeach
		  </select>
    </div>

    <div class="form-group col-md-2">
      <span> <input type="checkbox" name="costo" value="1"> Con Costo </span>
    </div>

    <input type="hidden" name="tipo_reporte" value="pdf">

    <div class="form-group col-md-2 col-md-offset-2">
      @include('partials.button_dropdown', [
        'name' => 'Generar Reporte',
        'className' => 'enviar-reporte',
        'options' => [
          ['text' => 'PDF', 'class' => 'tipo-reporte-btn pdf' ],
          ['text' => 'Excell', 'class' => 'tipo-reporte-btn excell' ],
        ]]);
      <a href="{{ route('home') }}" class="btn btn-danger"> Salir </a>
    </div>
  </div>
  
  @component('components.table', [
    'id' => 'datatable',
    'class_name' => 'sainfo-noicon size-9em',
    'thead' => [ 'Codigo' , 'Nombre', 'Seleccionar todo']
  ])
  
  @slot('body')
  @foreach( $grupos as $grupo )
  <tr>
    <td> {{ $grupo->GruCodi }} </td>
    <td> {{ $grupo->GruNomb }} </td>
    <td> <input type="checkbox" class="grupos-form" name="codi[]" value="{{ $grupo->GruCodi }}"></td>
  </tr>
  @endforeach
  @endslot
  
  @endcomponent
  
</form> 

@endslot

@endview_data
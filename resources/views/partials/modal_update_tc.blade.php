@php

$info = App\TipoCambioMoneda::getTodayInfo();

$needUpdated = $info['needUpdated'];
$tc = $info['tc'];

$title = $needUpdated ? "Establecer tipo de cambio" : 'Tipo de cambio del dia';

@endphp

@component('components.modal', ['id' => 'modalTC' , 'size' => 'modal-md', 'title' => $title ])
  @slot('body')
	  <form id="formTC" style="" data-info="{{ (int) $needUpdated }}" method="post" action="{{ route('tipo_cambio.updatedToday' , $tc->id ) }}">  
        
      @method('post')
      @csrf

      <p class="form-control text-center"> 
          Ultima Tipo de cambio: {{ $tc->fecha }} 15:00:00
      </p>

      <br>

      <div class="row">
        
        <div class="col-md-6">
          <div class="input-group">
          <span class="input-group-addon">Venta </span>
          <input class="form-control" type="text" name="TipVent" value="{{ $tc->venta }}">
          </div>
        </div>

        <div class="col-md-6">
        <div class="input-group">
          <span class="input-group-addon">Compra </span>
          <input class="form-control" type="text" name="TipComp" value="{{ $tc->compra }}">
        </div>
        </div>

      </div>

      <br>
      
	    <button class="btn btn-default"> <span class="fa fa-save"> </span> Guardar </button>
      <a href="https://e-consulta.sunat.gob.pe/cl-at-ittipcam/tcS01Alias" target="_blank" class="btn btn-primary pull-right">
        <span class="fa  fa-external-link"> </span>   Sunat </a>
    
      
    </form>
  @endslot

@endcomponent 
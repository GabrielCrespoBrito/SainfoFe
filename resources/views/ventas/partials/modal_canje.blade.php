@php
  $limit = $limit ?? 100;
@endphp

@component('components.modal', ['id' => 'modalCanje' , 'title' => 'Canjear Notas de Venta', 'size' => 'modal-lg' ])
  @slot('body')

    <div class="row" style="margin-bottom:10px">
      <div class="col-md-12">
        <a href="#" class="btn btn-flat btn-primary import-canje" class="btn btn-flat btn-primary"> 
        <span class="fa fa-download"></span> Aceptar </a>

        <span class="limits-box default pull-right" data-status="default"> 
            <span class="value-limit current" data-value="0">0</span> 
            <span class="separator">/</span>              
            <span class="value-limit max" data-value="{{ $limit }}"> {{ $limit }} </span>

        </span>


      </div>

    </div>

    @component('components.table', [ 'url' => route('ventas.search_canje') , 'id' => 'table_canje' , 'thead' => [ '<input type="checkbox" class="select-all">', 'Correlativo' , 'Cliente', 'Fecha' , [ 'class_name' => 'text-right-i', 'text' => 'Importe']  ]])
    @endcomponent

  @endslot
@endcomponent
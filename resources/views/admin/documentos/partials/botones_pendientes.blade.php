@php
  $consultStatus = $consultStatus ?? true;
@endphp
<div class="row">
  <div class="col-md-12">

    <a href="#" data-toggle="tooltip" title="Enviar seleccionados" class="btn btn-primary btn-flat pull-right enviar-sunat ml-x3"> 
      Enviar Sunat </a>

    @if( $consultStatus )
    <a href="#" data-toggle="tooltip" title="Consultar Estatus seleccionados" class="btn btn-primary btn-flat pull-right consultar-sunat ml-x3">
      Consultar Sunat </a>
    @endif


    <a href="#" id="select_all" class="btn btn-default btn-flat pull-right"> 
      <span class="fa fa fa-list-ul"> </span> Seleccionar/Quitar Todos
    </a>

  </div>
</div>


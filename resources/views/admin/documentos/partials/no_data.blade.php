<div class="row no-data-pendientes">

  <div class="col-md-12">
    <p class="title-data"> <span class="fa fa-check"> </span> No hay {{ $name }} pendientes </p>
    <p class="data-update">
      <span class="descripcion"> Ult. Fecha busqueda </span>
      <span class="value date-search-pendientes"> {{ $empresas_pendientes->updated_at }} </span>
      <a href="#" id="refresh-pendientes" data-route="{{ $route  }}" class="btn btn-xs btn-flat btn-default"> <span class="fa fa-refresh"> </span> Consultar </a>
    </p>
  </div>

</div>
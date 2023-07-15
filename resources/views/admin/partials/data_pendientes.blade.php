<div class="row data-pendientes">

  {{-- Total de empresas con documentos pendientes  --}}
  <div class="col-md-4 info-div">
    <div class="info">
      <span class="info-container">
      <span class="descripcion"> Empresas </span>
      <span class="value"> {{ $empresas_pendientes->data->total_empresas }} </span>
      </span>
    </div>
  </div>

  {{-- Total de documentos pendientes  --}}
  <div class="col-md-4 info-div">
    <div class="info">
      <span class="info-container">
        <span class="descripcion"> Total Pendientes </span>
        <span class="value"> {{ $empresas_pendientes->data->total_docs }} </span>
      </span>
    </div>
  </div>

  {{-- Ultima Fecha busqueda --}}
  <div class="col-md-4 info-div">
    <div class="info">
      <span class="info-container">
        <span class="descripcion"> Ult. Fecha busqueda </span>
        <span class="value date-search-pendientes"> {{ $empresas_pendientes->updated_at }} </span>
        <a 
          href="#"
          id="refresh-pendientes"        
          data-route="{{ $routeUpdatePendiente  }}"
          class="btn btn-xs btn-flat btn-default"> <span class="fa fa-refresh"> </span> 
        </a>        
      </span>
    </div>
  </div>

</div>
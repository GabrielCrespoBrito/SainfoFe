@php
  $entidad = $entidad ?? 'Cliente';
  // $route = $entidad == 'Cliente' ? route('clientes.buscar_cliente_select2') : route('proveedor.search');
  $route = $entidad == 'Cliente' ? route('clientes.ventas.search') : route('proveedor.search');
  
@endphp
<div class="row"> 
  <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon open-data" data-element="cliente">{{ $entidad }} </span>

        @if( ($accion ==  "create" || $accion ==  "edit")  && $importar == false )
        <input data-namedb="tipo_documento_c" class="form-control input-sm" name="tipo_documento_c" value="{{ optional($guia->cliente)->tipo_documento }}" placeholder="Numero documento" type="hidden">
        
        <div class="fixed_position">
          <select data-search="true" id="cliente_documento" data-tipo_documento="{{ optional($guia->cliente)->tipo_documento }}" data-url="{{ $route }}"  data-cliente="{{ $guia->cliente->exists ? $guia->cliente : ''  }}"  name="cliente_documento" class="form-control input-sm select2" style="position:absolute;"></select>
        </div>

        <span class="input-group-addon"> 
          <a href="#" id="newCliente"  class="btn btn-xs btn-default"><span class="fa fa-plus"></span> </a> 
        </span>

        @else 
          @php 
            $cliente = $importar ? $importar->cliente : $guia->cliente;
          @endphp

          <input id="cliente_documento" data-search="false" class="form-control input-sm" value=" {{ $cliente->PCNomb . ' ' . $cliente->PCRucc }}" readonly="readonly">

        @endif
    </div>
  </div>
</div>
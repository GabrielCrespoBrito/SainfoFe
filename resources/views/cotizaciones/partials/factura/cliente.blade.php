@php
  $id = "";
  $razon_social = "";
  $tipo_documento_cliente = '';  
  if( $is_orden ){
    if( $orden_cliente['exist'] ){
      $cliente = $orden_cliente['clienteSainfo'];
      $id = $cliente->PCRucc;
      $razon_social = $cliente->descripcion;
      $tipo_documento_cliente = $cliente->TDocCodi;      
    }
  }
  else {
  if( isset($cotizacion) ){
  }
  }
@endphp

<div class="row"> 



  @if( $is_orden )
  @if( ! $orden_cliente['exist'] )
  <div class="col-md-12- col-xs-12 table-error-cliente table-orden-error cliente">
    <div class="title-error"> <span class="fa fa-exclamation-circle"> </span> El cliente suministrado de documento <strong>{{ $orden_cliente['data']['documento'] }}</strong> no se encuentra registrado, en la base de datos del sistema, por favor presiona 
      <a class='btn btn-xs newCliente'> aqui </a> para registrarlo, o presione el boton para agregar clientes.
       </div>
  </div>
  @endif
  @endif

  <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon open-data" data-element="cliente">{{ $nombre_entidad }}</span>
        @if( $create || $modify )
        <input data-namedb="tipo_documento_c" class="form-control input-sm" value="{{ $cliente_tipo }}" name="tipo_documento_c" placeholder="Numero documento" type="hidden">
        <div class="fixed_position">
          <select id="cliente_documento" data-id="{{ $cliente_id }}" data-text="{{ $cliente_descripcion }}" data-url="{{ $routeSearchCliente }}" name="cliente_documento" class="form-control input-sm select2" style="position:absolute;"></select>
        </div>
        <span class="input-group-addon">           
          <a href="#" class="btn btn-xs btn-default newCliente"><span class="fa fa-plus"></span> </a> 
        </span>
        @else 
          <input class="form-control input-sm" value="{{ $cotizacion->cliente->PCRucc }}" readonly="readonly">
        @endif
    </div>
  </div>
</div>
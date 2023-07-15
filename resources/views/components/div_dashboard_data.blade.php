@php
  // $dataDocument = (object) $data[$codigo];
  $dataDocument = $data  [$codigo];
  $isGuia = $isGuia ?? false;

  if( $isGuia ){
    $routeNameEnviado = route(  'guia.index' , ['format' => true , 'status' => '0', 'mes' => date('Ym') ]);
    $routeNamePorEnviar = route( 'guia.pendientes' , [ 'mes' => date('Ym') ]);
    $routeNameNoAceptadas = "#";
  }
  else {
    $routeNameEnviado = route( 'ventas.index' , ['status' => '0001' , 'tipo' => $codigo, 'mes' => date('Ym') ]);
    $routeNamePorEnviar = route( 'ventas.index' , ['status' => '0011' , 'tipo' => $codigo, 'mes' => date('Ym') ]);
    $routeNameNoAceptadas = route( 'ventas.index' , ['status' => '0002' , 'tipo' => $codigo , 'mes' => date('Ym') ]);
  }

@endphp

<div class="small-box {{ $dataDocument->need_action ? 'action-user' : '' }}" data-id="{{ $codigo }}">

  <div class="inner">

    <h3 class="data total"> 
      <span class="data_total">{{ $dataDocument->total }}</span> <small>totales</small> 
    </h3>

    <div class="row">

    <div class="data_documento col-md-12"> <span class="data enviadas">       
      <a target="_blank" href="{{  $routeNameEnviado }}">
        <span class="value">{{ $dataDocument->enviadas }}</span> enviadas  
      </a>
    </div>

    <div class="data_documento col-md-12"> <span class="data no_enviadas {{ (bool)  $dataDocument->por_enviar ? 'action' : ''}}"> 
    <a target="_blank" href="{{ $routeNamePorEnviar }}">
      <span class="value"> {{ $dataDocument->por_enviar }} </span>  por enviar
    </a>
    </div>

 
    <div class="data_documento col-md-12"> <span class="data no_aceptadas {{ (bool)  $dataDocument->no_aceptadas ? 'action' : '' }}"> 
    <a target="_blank" href="{{ $routeNameNoAceptadas }}">
      <span class="value"> {{ $dataDocument->no_aceptadas }} </span>  no aceptadas
    </a>
    </div>

    </div>

    <p> {{ $nombre }} </p>
      <div class="icon">
        <i class="fa fa-file-o"></i>
      </div>


  </div>

</div>
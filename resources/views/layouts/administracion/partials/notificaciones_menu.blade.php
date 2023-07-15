@php

  if( !isset($data_notificacion) ){
    $data_notificacion = [
      'por_vencer' => false,
      'total' => 0,
      'boletas_hoy' => 0,
      'boletas_todo' => 0,
      'boletas_vencer' => 0,
      'facturas_hoy' => 0,
      'facturas_todo' => 0,
      'facturas_vencer' => 0,
    ];
  }

@endphp

<!-- Documentos pendientes -->
<li class="dropdown notifications-menu">
  <a href="#" class="dropdown-toggle {{ $data_notificacion['por_vencer'] ? 'danger' : '' }}"  data-toggle="dropdown">
    <i class="fa fa-bell-o"></i>
    <span class="label label-danger">{{ $data_notificacion['total'] }}</span>
  </a>
  <ul class="dropdown-menu" style="box-shadow: 0 0 10px 4px #999">
      @if($data_notificacion['total'])
    <li class="header"> 
       <small class="label pull-right  bg-gray"> {{ $data_notificacion['total'] }} </small> Ay documentos requiren su atención 
      </li>
      @else
    <li class="header " style="text-align: center">       
        No ay documentos por enviar <span style="color: green" class="fa fa-thumbs-o-up"></span>
      @endif
    </li>
    <li>
      <!-- inner menu: contains the actual data -->
      <ul class="menu">
        @if($data_notificacion['total'])


        <li>
          <?php 
            $factura = App\NotificacionDocumentosPendientes::FACTURA;
            $boleta = App\NotificacionDocumentosPendientes::BOLETA;
            $todo = App\NotificacionDocumentosPendientes::LAPSO_TODO;
            $vencer = App\NotificacionDocumentosPendientes::LAPSO_VENCER;
          ?>
          <a href="{{ route('documentos.pendientes', [ $factura , $todo ] ) }}"><small class="label pull-right bg-gray">{{ $data_notificacion['facturas_todo'] }}</small> Facturas por enviar </a>
          
          @if($data_notificacion['facturas_vencer'])          
          <a class="alerta" href="{{ route('documentos.pendientes', [$factura , $vencer]) }}">  <span style="color: red" class="fa fa-bell-o"></span> <small class="label pull-right  bg-gray">
            {{ $data_notificacion['facturas_vencer'] }}
          </small> Facturas por vencer </a>          
          @endif

          <a href="{{ route('documentos.pendientes', [ $boleta , $todo ] ) }}"> <small class="label pull-right  bg-gray">{{ $data_notificacion['boletas_todo'] }}</small> Boletas por enviar </a>

          @if($data_notificacion['boletas_vencer'])
          <a class="alerta" href="{{ route('documentos.pendientes', [$boleta , $vencer] ) }}"> <span style="color: red" class="fa fa-bell-o"></span> 
            <small class="label pull-right  bg-gray">{{ $data_notificacion['boletas_vencer'] }}</small> Boletas por vencer 
          </a>
          @endif

        </li>
        @else 
        @endif
      </ul>
    </li>
  </ul>
</li>
<!-- /Documentos pendientes -->

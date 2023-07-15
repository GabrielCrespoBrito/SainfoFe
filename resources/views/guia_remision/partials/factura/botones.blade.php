@php
  // route_index
  $canModify = $guia->canModify();
  $isIngreso = $guia->isIngreso();
  $routeIndex = $routeIndex ?? ($isIngreso ? route('guia_ingreso.index', ['format' => 1]) : route('guia.index', ['format' => 1 ] ));
  $isSalida = ! $isIngreso;
  $fe_rpta = $guia->fe_rpta;
  $nombreGenerador = $isIngreso ? 'Generar Compra' : 'Generar Venta';
@endphp

<div class="botones row">

	<div class="col-md-6 col-lg-6 col-sm-6">

    @if( $fe_rpta == "0" )
      <p href="#" style="padding: 5px" class=" bg-green ">{{  $guia->fe_obse }} </p>
    @else
		
    @if( $canModify == false  )
      <div class="row">
        <div class="col-md-12">
          <p href="#" class="border" style="border: 1px solid black; padding: 5px"> Guia de solo lectura
            <a class="btn btn-xs btn-success"> {{ $guia->docrefe }} </a>
          </p>
        </div>
      </div>
    @endif
    
    <div class="pull-left">
      @if( ($accion == "create" || $accion == "edit") && $canModify == true )
        <a href="#" class="btn btn-primary btn-flat save_guia"><span class="fa fa-save"> </span> Guardar </a>
        @if(!$isIngreso)
        @endif
      @endif
		</div>
    
    {{-- cpaOper --}}
    <div class="pull-right">
      @if( $accion == "edit" && $isSalida)
          @if( $guia->canChangeDespacho() )
        <a href="#" class="btn btn-default btn-flat" data-toggle="modal" 
        id="showModalDespacho"> Despachar</a>
        @endif    
      @endif
    </div>
    
    @endif
  </div>
  

  {{-- Right --}}



  <div class="col-md-6 col-lg-6">
    @if($isSalida && $accion != "create")
      @if(!$guia->isGuiaTransportista())

      @if($guia->haSidoTrasladada())
        <a class="btn btn-default btn-flat" href="{{ route('guia_ingreso.edit' , $guia->CtoOper ) }}" target="_blank">
          Trasladado a almacen {{ $guia->CtoOper }} </a>
      @else
        <a href="#" id="trasladoAlmacen" class="btn btn-primary btn-flat"> Traslado de almacen </a>
      @endif
      @endif
    @endif

    <a href="{{ $routeIndex }}" data-is_ingreso="{{ $isIngreso }}" class="btn btn-danger index-page btn-flat pull-right link-redirect"> Salir </a>
    
    @if($fe_rpta == "0" )
      <a href="#" data-url="{{ route('guia.verificar' , $guia->GuiOper ) }}" class="btn btn-default btn-flat pull-leftt" id="validar">  Validar </a>    
    @endif

   @if( $accion == "create" )

    @else




    @if( $guia->isCerrada() )

      <a href="{{ route('guia.pdf', [ 'id' => $guia->GuiOper]  ) }}" target="_blank" class="btn btn-default btn-flat pull-right"> <span class="fa fa-file"></span> Impresión</a>

      @if( $guia->isCerrada() && ! $isIngreso )
        @if( $guia->isAnulada() )
          <a href="#" class="btn btn-default disabled btn-flat pull-right"> Guia anulada </a>
        @else
          <a href="#" class="btn btn-danger anular_documento btn-flat pull-right"> <span class="fa fa-file"></span>  Anular </a>
        @endif
      @endif

    @endif
    

    @if( $isIngreso || ($guia->hasFormato() && $canModify) )
      <a href="#" target="_blank" class="btn btn-default btn-flat pull-right" data-toggle="modal" data-target="#modalGenerarDoc" > <span class="fa fa-file"></span> {{ $nombreGenerador }} </a>
    @endif


    @endif

    @php
      $routeIndex = $isIngreso ? route('guia_ingreso.index') : route('guia.index'); 
    @endphp

  </div>
</div>
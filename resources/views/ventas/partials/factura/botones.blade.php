<div class="botones row">

  <!-- left -->
  <div class="col-md-5 col-lg-5 col-sm-12 no_pr">
    {{-- guardar --}}
    @if( $create )
    <a href="#" class="btn btn-primary btn-flat" id="guardarFactura">
      <span class="fa fa-save"></span> Guardar
    </a>

    <a href="#" class="btn btn-default btn-flat" id="previsualizar"> <span class="fa fa-eye"></span> Visualizar </a>

    {{-- importar --}}
    <a href="#" class="btn btn-default btn-flat importar_accion">
      <span class="fa fa-download"></span>
    </a>


    {{-- condicion de  venta --}}
    <a href="#" class="btn btn-default btn-flat condi_venta">
      <span class="fa fa-file-text-o"></span>
    </a>

    <a href="#" id="salir_" class="btn btn-danger btn-flat" id="salir"><span class="fa fa-power-off"></span></a>

    @if( $modulo_canje_nv )
      <a href="#" class="btn btn-primary btn-flat" id="canje_nv"><span class="fa fa-exchange"></span> Canje Notas de Venta</a>
    @endif

    @else
    @if($venta->isAnulada())
    <a href="#" class="btn disabled btn-default btn-flat">
      Documento Anulado
    </a>
    @endif
    @endif


  </div>
  <!-- /left -->


  <!-- right -->
  <div class="col-md-7 col-lg-7 col-sm-12">

    <div class="pull-right">

      @if(!$create)

      {{-- Verificar el ticket --}}
      @if( $venta->isAnulada() && $venta->isDocumentoSunat() )
      <a href="#" class="btn btn-default btn-flat verificar_ticket" data-toggle="tooltip" title="Validar ticket"><span class="fa fa-send-o"></span> </a>
      @endif

      {{-- Pagos de venta --}}
      @if( get_option('OpcConta'))
      <a href="#" class="btn {{ $create ? 'disabled' : '' }} btn-default btn-flat pagos-button"> <span class="fa fa-money"></span> Pagos </a>
      @endif
      @endif

      <span class="group-sunat" style="margin-left:20px">
        @if( ! $create )
        @if( $venta->VtaEsta != "A" )
        <a href="#" class="btn anular_documento btn-default btn-flat"> <span class="fa fa-ban"></span> Anular</a>
        @endif        
        @endif
        @if(!$create )
        @if( auth()->user()->can('enviar-email-documento-mail'))
        <a href="#" class="btn btn-default btn-flat enviar_mail"> <span class="fa fa fa-envelope"></span> Enviar Mail</a>
        @else
        <a href="#" class="btn btn-default readdonly btn-flat" data-toggle="tooltip" title="No puede enviar email al cliente, por que no tiene email asociado"> <span class="fa fa fa-envelope"></span> </a>
        @endif
        @endif
      </span>
    </div>
  </div>
</div>
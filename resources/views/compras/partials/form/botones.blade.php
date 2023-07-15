<div class="botones row">

  <div class="col-md-12 col-lg-12 col-sm-12">
    @if( $active_form ) 
      <a href="#" class="btn btn-primary btn-flat" id="save_compra"> <span class="fa fa-save"> </span> {{ $create ? "Guardar"  : "Actualizar" }} </a>    
    @else
      
      <a href="{{ route('compras.edit', $compra->CpaOper) }}" class="btn btn-default btn-flat"> 
        <span class="fa fa-pencil"> </span> Editar </a>

      <a href="#" class="btn  btn-default btn-flat pagos-button"> <span class="fa fa-money"></span> Pagos </a>
      
    {{-- Guia --}}
    @if( $guia = $compra->guia )
      <a href="{{ route('guia.edit' , $compra->GuiOper ) }}" class="btn btn-default btn-flat guia-button"> <span class="fa fa-money"></span> Guia </a>
    @else
      <a href="#" data-toggle="modal" data-target="#modalGuiaSalida"  class="btn btn-primary btn-flat guia-button"> <span class="fa fa-money"></span> Generar Guia </a>
      @include('ventas.partials.modal_guiasalida', ['url' => route('guia.store.ingreso', $compra->CpaOper ) , 'isCompra' => true, 'compra' => $compra  ])
    @endif
    {{-- /Guia --}}

    @endif

  @if($create)
    <a href="#" class="btn btn-default btn-flat" id="modal-importacion"> <span class="fa fa-download"> </span> Importar O. Compra  </a>
  @endif

  <a  href="{{ route('compras.index') }}" class="btn btn-danger pull-right btn-fla salir_button"> Salir </a>          

  <label class="ml-x10" for="incluye_igv"> Incluye IGV <input {{ $show ? 'disabled' : '' }}  {{ $compra->incluyeIgv() ? 'checked' : '' }}  type="checkbox" name="incluye_igv"> </label>


  </div>


</div>
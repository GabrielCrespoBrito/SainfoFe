@if( $GuiEsta == "A" )

<a href="#" class="btn btn-xs anulada">
  <span class="fa fa-spin"></span>
  Anulada
</a>

@else

  {{-- Si es salida --}}
  @if( $model->isSalida() )

    {{-- Si sta Aceptada o si no --}}
    @if( $fe_rpta == 9 )

    <a href="#" class="btn btn-xs por_enviar">
      <span class="fa fa-spin fa-spinner"></span>
      Por enviar  
    </a>

    @elseif( $fe_rpta == 0 )

    <a href="#" class="btn btn-xs aceptado">
      <span class="fa fa-check"></span>Aceptado  
    </a>

    @endif
    {{-- /Si sta Aceptada o si no --}}


  @else

    <a href="#" class="btn btn-xs aceptado">
      <span class="fa fa-check"></span>Activa
    </a>


  @endif
  {{-- /Si es salida --}}

@endif
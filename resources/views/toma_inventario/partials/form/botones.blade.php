
<div class="botones row">
  <div class="col-md-12 col-lg-12 col-sm-12 text-center">

    @if( $active_form ) 

      <a href="#" class="enviar-form btn btn-default btn-flat" data-type="P">
        <span class="fa fa-save"></span> Guardar
      </a>

      <a href="#" class="enviar-form btn btn-success btn-flat" data-type="C">
        <span class="fa fa-check"></span> Guardar y Finalizar

      </a>

    @else

      @if( $model->isPendiente() )
          <a href="{{ route('toma_inventario.edit', $model->id )  }}" class="btn-success btn btn-flat">
            <span class="fa fa-pencil"></span> Modificar
          </a>

      @endif

    @endif

    <a id="salir" href="{{ route('toma_inventario.index') }}" class="btn btn-default btn-flat"><span class="fa fa-"></span>Salir</a>

  </div>
</div>
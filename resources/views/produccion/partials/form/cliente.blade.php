<div class="row">   
  <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">Proveedor </span>
        @if( $active_form )
        <div class="fixed_position">
        <select id="cliente_documento" data-id="{{ $edit ? $compra->proveedor->PCCodi : '' }}" data-text="{{ $edit ? $compra->proveedor->PCNomb : '' }}" data-url="{{ route('proveedor.search') }}" name="PCcodi" class="form-control input-sm select2" style="position:absolute;"></select>
        </div>
        <span class="input-group-addon">
          <a href="#" id="newCliente"  class="btn btn-xs btn-default"><span class="fa fa-plus"></span> </a>
        </span>
        @else
          <input class="form-control input-sm" {{ setInputState($show) }} value="{{ $compra->proveedor->PCRucc . ' - ' . $compra->proveedor->PCNomb  }}" readonly="readonly">          
        @endif
    </div>
  </div>
</div>
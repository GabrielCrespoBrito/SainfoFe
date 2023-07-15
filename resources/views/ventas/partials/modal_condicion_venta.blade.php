@php
$hide_label = $hide_label ?? false;

@endphp

<div data-condicion="{{ $tipo_condicion }}" class="modal fade" id="modalCondicionVenta" data-comprobar_guardado="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Condición del Documento</h4>
      </div>
      <div class="modal-body">

        @if($create)
          <div class="explicacion-condicion mb-x10">
            <span class=" fa fa-info-circle"></span>
            <span class="explicacion-text">Separa las condiciones con un simbolo de menos (-)</span>
            <span data-open="false" class="btn-example-toggle pull-right"> 
              <span class="fa fa-info-circle"></span>Ejemplo</span>
            </span>
          </div>
          <div class="example-condicion explicacion-condicion mb-x10" style="display:none">
            <div> - condicion 1 </div>
            <div> - condicion 2 </div>
            <div> - condicion 3 </div>
          </div>        
        @endif

        <div class="div_condicion_venta">
          <div class="form-group">
            <textarea 
              name="condicion_venta"
              data-con_ven="{{ $condicion }}"
              data-con_cot="{{ $condicion_cot }}"
              rows="6" 
              {{ $create ? '' : 'readonly="readonly"' }} 
              class="form-control">{{ $condicion }}</textarea>
          </div>
        </div>        

        <div class="botones_div" style="margin-bottom: 10px">
          @if($create)
          <a class="btn guardar_condicion pull-left btn-success btn-flat">
            <span class="fa fa-save"> </span> Grabar</a>
          <a class="btn pull-left btn-danger btn-flat" data-dismiss="modal" aria-label="Close"> </span> Cancelar</a>
          <label   style="{{ $hide_label ? 'display:none' : ''  }}" data-toggle="tooltip" title="Si Desmarca la Selección, la condición que guarde se usara para los siguientes clientes" class="pull-right label-condicion-tipo-guardado"> <input name="uso_individual" type="checkbox" checked value=""> 
          <span style="1px dotted #ccc">Usar solo para este documento</span> </label>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
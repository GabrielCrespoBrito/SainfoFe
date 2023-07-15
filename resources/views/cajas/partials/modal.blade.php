@php

  $size = $tipo_movimiento == App\Control::CAJA ? 'modal-sm' : 'modal-md';  
  $titulo = "Nuevo ingreso";
  if($tipo_movimiento == App\Control::CAJA ){
    $titulo = "Apertura de caja";    
  }  
@endphp

<div class="modal modal-seleccion fade" id="modalAccion">

  <div class="modal-dialog {{ $size }}">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ $titulo }}</h4>
      </div>

      <div class="modal-body">
        <form id="form-movimiento">
          <input type="hidden" name="id_caja" value="{{ $caja->CajNume }}">
          <input type="hidden" name="id_movimiento" data-field="Id" value="">
          <input type="hidden" name="tipo_movimiento" value="{{ $tipo_movimiento }}">
          <input type="hidden" name="accion_movimiento" value="create">

          @if( $tipo_movimiento == App\Control::CAJA )          
            @include('cajas.partials.form_caja')

          @elseif( in_array($tipo_movimiento,App\Control::INGRESOS) )            
            @include('cajas.partials.form_ingreso')

          @elseif( in_array($tipo_movimiento,App\Control::EGRESOS) )            
            @include('cajas.partials.form_egreso')
          @endif          

        <div class="row">
          <div class="col-md-12">
            <button type="button" class="btn btn-success" id="guardar"> Guardar </button>     
            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"> Cancelar </button>
          </div>
        </div>


      </form>


      </div>
    </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


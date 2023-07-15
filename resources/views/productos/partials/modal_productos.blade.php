<div class="modal fade" id="modalAccion">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">      
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> Producto </h4>
      </div>
    
    <div class="modal-body">

    <form id="form-accion" enctype="multipart/form-data" onsubmit="return false;" >

    <div class="nav-tabs-custom">
      

      <ul class="nav nav-tabs nav-tab-main">        
        
        <li class="active">
          <a href="#tab_1" class="tab-info" data-toggle="tab" aria-expanded="true"> 
            <span class="fa fa-info"></span> Información </a>
        </li>
        
        <li class="">
          <a href="#tab_2" data-toggle="tab" aria-expanded="false"> 
          <span class="fa fa-table"></span> Referencia </a>
        </li>
        
        <li class=""><a href="#tab_3" class="tab-movimiento tabs-edit" data-toggle="tab" aria-expanded="false"> <span class="fa fa-exchange"></span> Movimientos </a>

        <li class=""><a href="#tab_4" class="tab-acciones tabs-edit" data-toggle="tab" aria-expanded="false"> <span class="fa fa-cogs"></span> Acciones </a>
        </li>

      </ul>

      <div class="tab-content">
        
        <!-- Tab 1 -->
        <div class="tab-pane active" id="tab_1">
          @include('productos.partials.form.principal')   
        </div> 
        <!-- /Tab 1 -->

        {{-- Tab 2 --}}
        <div class="tab-pane" id="tab_2">
          @include('productos.partials.form.adicional')
        </div>
        {{-- /Tab 2 --}}

        {{-- Tab 3 --}}
        <div class="tab-pane" id="tab_3">
          @include('productos.partials.movimientos')
        </div>
        {{-- /Tab 3 --}}

        {{-- Tab 4 --}}
        <div class="tab-pane" id="tab_4">
          @include('productos.partials.acciones')
        </div>
        {{-- /Tab 4 --}}

       </div>
        <!-- /.tab-content -->
      </div>

    <div class="row ">
      
      <div class="col-md-6 col-xs-12 div-btn-form-save">
        <a class="btn btn-primary btn-flat send_info"><span class="fa fa-save"></span> Guardar</a>
      </div>

      <div class="col-md-6 col-xs-12 div-btn-form-kardex">
        <a class="btn btn-success btn-flat send_info_kardex"><span class="fa fa-pdf"></span> Guardar Reporte</a>
      </div>

    </div>

    </form>

      {{-- --}}
      <form class="formReporteKardex" action="{{ route('productos.reporte_movimientos') }}" method="post">
        @csrf
        <input type="hidden" name="fecha_desde">
        <input type="hidden" name="fecha_hasta">
        <input type="hidden" name="articulo_desde">
        <input type="hidden" name="articulo_hasta">
        <input type="hidden" name="LocCodi">
      </form>
      {{-- --}}

    </div> <!-- modal-body  -->

    </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
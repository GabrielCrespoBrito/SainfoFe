@php
  $showButtonsSalir = $showButtonsSalir ?? true;
@endphp
        <div class="botones_div">

          <a class="btn btn-success btn-flat" id="pay_factura">  
            <span class="fa fa-check" > </span> Aceptar</a>

          @if( $showButtonsSalir )
          <a class="btn btn-danger btn-flat" id="salir_pago"> Salir</a>
          @endif

        </div>     

        <div class="facto_factura">
          <div class="row info_p">
            
            <div class="form-group col-md-4">  
              <div class="input-group">
                <span class="input-group-addon">Doc:</span>
                <input class="form-control input-sm" data-field="correlative" name="PVtaNume" disabled="disabled" value="">      
              </div>
            </div>

            <div class="form-group col-md-4">  
              <div class="input-group">
                <span class="input-group-addon">Fecha</span>
                <input class="form-control input-sm" data-field="fecha" name="ref_documento" disabled="disabled" value="">      
              </div>
            </div>

            <div class="form-group col-md-4">  
              <div class="input-group">
                <span class="input-group-addon">Por Pagar.: S/.</span>
                <input class="form-control input-sm" data-field="saldo" name="i" disabled="disabled" value="">
              </div>
            </div>

          </div>
        </div>

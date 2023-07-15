
@php 
  $is_guia = isset($is_guia);
@endphp

<div class="filtros">

  <!-- Articulo -->
  <div class="filtro" id="condicion">
    <div class="cold-md-12">
      <fieldset class="fsStyle">      
        <legend class="legendStyle">Fechas (desde,hasta)</legend>

        <div class="row" id="demo">
          
          <?php $date = date('Y-m-d'); ?>           

          <div class="col-md-6">
            <input type="text" value="{{ $date }}" name="fecha_desde" class="form-control input-sm datepicker no_br flat text-center">  

          </div>

          <div class="col-md-6">
            <input type="text" value="{{ $date }}" name="fecha_hasta" class="form-control input-sm datepicker no_br flat text-center">  
          </div>
        
        </div>                  

      </fieldset>
    </div>
  </div>
  <!-- Articulo --> 

	<!-- Condicion de venta -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Cliente y tipo de documento </legend>
				<div class="row" id="demo">
					<div class="col-md-6">
            <select name="cliente" class="form-control">
              <option value="todos"> TODOS </option>
              @foreach( $clientes as $cliente)
                <option value="{{ $cliente->PCCodi }}">  {{ $cliente->PCNomb }} </option>                  
              @endforeach
            </select>
					</div>

          <div class="col-md-6">
            <select class="form-control" name="local">
              @foreach( $almacenes as $almacen )
              <option value="todos"> TODOS </option>              
                @if($almacen->elegible())
                  <option  value="{{ $almacen->LocCodi }}" {{ $almacen->default() ? 'selected=selected' : ''  }} >{{ $almacen->LocNomb }}</option>
                @endif
              @endforeach            
            </select>
          </div> 


			  </div>
		  </fieldset>
		</div>
	</div>
	<!-- Condicion de venta -->



@if($is_guia)
  {{-- Filtro por estado  --}}
  <!-- Condicion de venta -->
  <div class="filtro" id="condicion">
    <div class="cold-md-12">
      <fieldset class="fsStyle">      
        <legend class="legendStyle"> Formato y Estado en la Sunat </legend>
        <div class="row" id="demo">

          <div class="col-md-6">
            <select class="form-control" name="formato">
              <option value="todos"> TODOS </option>   
              <option value="1"> Formato </option>   
              <option value="0"> Sin formato </option>   
            </select>
          </div>  

          <div class="col-md-6">
            <select class="form-control" name="estado_sunat">
              <option value="todos"> TODOS </option>   
              <option value="9"> Pendiente </option>   
              <option value="0"> Aceptado </option>   
              <option value="999"> Rechazados </option>                 
            </select>
          </div>

        </div>
      </fieldset>
    </div>
  </div>
  <!-- Condicion de venta -->
@endif







  @if( !$is_guia )

  <!-- Condicion de venta -->
  <div class="filtro" id="condicion">
    <div class="cold-md-12">
      <fieldset class="fsStyle">      
        <legend class="legendStyle">Tipo documento, serie y vendedor </legend>
        <div class="row" id="demo">
          <div class="col-md-4">
            <select class="form-control" name="tipo_documento">
              <option value="todos"> TODOS </option>                            }
              @foreach( $tipos_documentos as $documento )
                <option value="{{ $documento['id'] }}">{{ $documento['nombre'] }}</option>                
              @endforeach              
            </select>
          </div>

          <div class="col-md-2">
            <select class="form-control" name="serie">
                <option value="todos"> TODOS </option>                            
                <option>001</option>
                <option>002</option>
                <option>003</option>
                <option>004</option>
                <option>005</option>
                <option>006</option>
                <option>007</option>                
            </select>
          </div> 

          <div class="col-md-6">
            <select class="form-control" name="vendedor">
              <option value="todos"> TODOS </option>                                          
              @foreach( $vendedores as $vendedor )
                <option value="">{{ $vendedor->vennomb }}  </option>
              @endforeach            
            </select>
          </div> 
        </div>
      </fieldset>
    </div>
  </div>
  <!-- Condicion de venta -->

  @endif
</div>




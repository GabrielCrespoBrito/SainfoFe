
@php 
  $is_guia = isset($is_guia);
  $fechas = fechas_reporte();
@endphp

<form method="post" action="{{ $route }}">

@csrf

<input type="hidden" name="is_ingreso" value="{{ $isIngreso }}"/>

<div class="filtros">

  <!-- Articulo -->
  <div class="filtro" id="condicion">
    <div class="cold-md-12">
      <fieldset class="fsStyle">      
        <legend class="legendStyle">Fechas (desde,hasta)</legend>

        <div class="row" id="demo">
          
          <div class="col-md-6">
            <input type="date" value="{{ $fechas->inicio }}" name="fecha_desde" class="form-control input-sm no_br flat text-center">  

          </div>

          <div class="col-md-6">
            <input type="date" value="{{ $fechas->final }}" name="fecha_hasta" class="form-control input-sm no_br flat text-center">  
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
        <legend class="legendStyle">{{ $isIngreso ?  "Motivo , Usuario" : "Motivo , Tipo Movimiento, Usuario" }}  </legend>
        <div class="row" id="demo">
          <div class="{{ $isEgreso ? 'col-md-4' : 'col-md-6' }}">
            <select class="form-control" name="motivo">
              <option value="todos"> -- TODOS -- </option>                                          
              @foreach( $motivos as $motivoId => $motivoNombre  )
                <option value="{{ $motivoId }}">{{ $motivoNombre }}</option>                
              @endforeach              
            </select>
          </div>

        @if( $isEgreso )
         <div class="col-md-4">
            <select class="form-control" name="tipo_movimiento">
              <option value="todos"> -- TODOS -- </option>                                          
              @foreach( $tipoMovimientos as $tipoMovimientoId => $tipoMovimientoNombre  )
                <option value="{{ $tipoMovimientoId }}">{{ $tipoMovimientoNombre }}</option>                
              @endforeach              
            </select>
          </div>
        @endif


          <div class="{{ $isEgreso ? 'col-md-4' : 'col-md-6' }}">
            <select class="form-control" name="usuario">
              <option value="todos"> -- TODOS -- </option>                                          
              @foreach( $usuarios as $usuarioId => $usuarioNombre )
                <option value="{{ $usuarioId }}">{{ $usuarioNombre }} </option>
              @endforeach            
            </select>
          </div> 
        </div>
      </fieldset>
    </div>
  </div>

  <!-- Tipo de Reporte -->
  <div class="filtro" id="condicion">
    <div class="cold-md-12">
      <fieldset class="fsStyle">      
        <legend class="legendStyle"> Tipo de Reporte </legend>
        <div class="row" id="demo">
          <div class="col-md-12">
            <select class="form-control" name="tipo_reporte">
              <option value="pdf"> PDF </option>                                          
              <option value="excell"> Excell </option>                                          
            </select>
          </div>
        </div>
      </fieldset>
    </div>
  </div>
  <!-- Tipo de Reporte -->


</div>

{{--  --}}


<div class="row">

<div class="col-md-12">

<button type="submit" class="btn btn-flat btn-primary"> Buscar </button>

</div>

{{--  --}}
</form>
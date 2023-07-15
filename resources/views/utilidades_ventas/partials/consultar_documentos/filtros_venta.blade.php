

<div class="filtros">


@include('components.block_elemento')


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
            <input type="text" value="{{ $date }}" name="fecha_hasta" class="form-control input-sm datepicker no_br flat text-center"></div>
        
        </div>                  

      </fieldset>
    </div>
  </div>
  <!-- Articulo --> 


	<!-- Condicion de venta -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Tipo de documento y serie </legend>
				<div class="row" id="demo">
					<div class="col-md-6">
            <select name="tipo_documento" class="form-control">
              <?php $documentos_aceptados = "01 03 07 08"; ?>              
              @foreach( $tipos_documentos as $documento )    
              @if( strpos( $documentos_aceptados , $documento["id"] ) !== false  )          
                <option data-series="{{ json_encode($documento['series']) }}" value="{{ $documento['id'] }}"> {{ $documento['nombre'] }} </option>
              @endif
              @endforeach
            </select>
					</div>

          <div class="col-md-6">
            <select name="serie_documento" class="form-control">
              @foreach( $tipos_documentos->first()['series'] as $serie )              
                <option value="{{ $serie['id'] }}"> {{ $serie['nombre'] }} </option>
              @endforeach
            </select>
          </div>          
			  </div>
		  </fieldset>
		</div>
	</div>
	<!-- Condicion de venta -->


  <!-- Condicion de venta -->
  <div class="filtro" id="condicion">
    <div class="cold-md-12">
      <fieldset class="fsStyle">      
        <legend class="legendStyle">Correlativo desde y hasta </legend>
        <div class="row" id="demo">
          <div class="col-md-6">
            <input type="text" class="form-control" name="correlativo_desde" value="{{ $rangos['desde'] }}">
          </div> 

          <div class="col-md-6">
            <input type="text" class="form-control" name="correlativo_hasta" value="{{ $rangos['hasta'] }}">
          </div> 
        </div>
      </fieldset>
    </div>
  </div>
  <!-- Condicion de venta -->

  @component('components.table' , [
  'id' => 'table_documentos' ,
  'thead' => [
      "Item" ,
      "Nro documento",
      "Fecha",
      "Estado",
    ]
  ])

  @endcomponent





</div>




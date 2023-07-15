<form action="{{ route('reportes.compras_pdf') }}" method="post">
@csrf

@php 
  $is_guia = isset($is_guia);
  $fechas = fechas_reporte();


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
            <input 
            type="text" 
            value="{{ $fechas->inicio }}" 
            name="fecha_desde" 
            data-date-format="yyyy-mm-dd"
            class="form-control input-sm datepicker no_br flat text-center">  

          </div>

          <div class="col-md-6">
            <input 
            type="text" 
            value="{{ $fechas->final }}" 
            name="fecha_hasta" 
            data-date-format="yyyy-mm-dd"
            class="form-control input-sm datepicker no_br flat text-center">  
          </div>
        
        </div>                  

      </fieldset>
    </div>
  </div>
  <!-- Articulo --> 

	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Proveedor , Tipo de documento y Tipo de Reporte </legend>
				<div class="row" id="demo">

					<div class="col-md-6">
            <select name="proveedor" class="form-control">
              <option value="todos"> TODOS </option>
              @foreach( $proveedores as $proveedor)
                <option value="{{ $proveedor->PCCodi }}">  {{ $proveedor->PCNomb }} </option>                  
              @endforeach
            </select>
          </div>

					<div class="col-md-4">
            <select name="tipodocumento" class="form-control">
              <option value="todos"> TODOS </option>
              <option value="01"> FACTURA </option>
              <option value="03"> BOLETA </option>
              <option value="07"> NOTA CREDITO </option>
              <option value="08"> NOTA DEBITO </option>
              <option value="40"> COMPRA LIBRE </option>
            </select>
          </div>

					<div class="col-md-2">
            <select name="tiporeporte" class="form-control">
              <option value="pdf"> PDF </option>
              <option value="excell"> EXCELL </option>
            </select>
          </div>


			  </div>
		  </fieldset>
		</div>
	</div> 


</div>

  <div class="col-md-12">
    <button type="submit" class="btn btn-primary"> Generar </button>
    <a href="{{ route('home') }}" class="btn btn-danger pull-right"> Salir </a>

  </div>

</form>
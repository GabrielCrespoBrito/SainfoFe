@php
  $fecha_final = date('Y-m-d');

	
@endphp

<div class="filtros">

	<!-- Condicion de venta -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Condiciones </legend>
				<div class="row" id="demo">
					<div class="col-md-6">
						<div class="radio">
		          <label>
		            <input type="radio" name="condicion_articulo" value="compras" checked="checked"> Compras por articulos </label>
		        </div>
					</div>


					<div class="col-md-6">
						<div class="radio">
		          <label>
		            <input type="radio" name="condicion_articulo" value="ventas"> Ventas por articulos </label>
		        </div>
					</div>
			  </div>
		  </fieldset>
		</div>
	</div>
	<!-- Condicion de venta -->

	<!-- Articulo -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Articulos </legend>

				<div class="row" id="demo">

					<div class="col-md-3">
						<div class="form-group">
		        	<input type="text" placeholder="Codigo" name="codigo" class="form-control input-sm codigo"  value="{{ $producto_id }}"> 
		       	</div>
		      </div>

					<div class="col-md-9">
						<div class="form-group">
		          <input type="text" placeholder="Nombre del articulo" name="nombre" class="form-control input-sm nombre" value="{{ $producto_nombre }}">
		        </div>
					</div>	
								
				</div>	

	  </fieldset>
		</div>
	</div>
	<!-- Articulo -->	



	<!-- Articulo -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Fechas (desde,hasta)</legend>

				<div class="row" id="demo">

			    <div class="col-md-6">
			      <input type="text" value="{{ $fecha_inicio }}" name="fecha_desde" class="form-control input-sm datepicker no_br flat text-center">  

			    </div>

			    <div class="col-md-6">
			      <input type="text" value="{{ $fecha_final }}" name="fecha_hasta" class="form-control input-sm datepicker no_br flat text-center">  
			    </div>


				
				</div>									

		  </fieldset>
		</div>
	</div>
	<!-- Articulo -->	


	<!-- Totales -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Totales ( <span>total cantidad</span> , <span>ultimo costo</span>, <span>costo promedio</span>)</legend>

				<div class="row" id="demo">

			    <div class="col-md-4">
			      <input type="text" value="" disabled="disabled" placeholder="Total Cantidad" class="form-control input-sm total_cantidad no_br flat text-center">  

			    </div>

			    <div class="col-md-4">
			      <input type="text" value="" disabled="disabled" placeholder="Ultimo Costo" class="form-control input-sm ultimo_costo no_br flat text-center">  
			    </div>

		    <div class="col-md-4">
			      <input type="text" value="" disabled="disabled" placeholder="Costo Promedio" class="form-control input-sm costo_promedio no_br flat text-center">  
			    </div>

				
				</div>									

		  </fieldset>
		</div>
	</div>
	<!-- Articulo -->	



</div>




<!-- Articulo -->
<div class="filtro" id="condicion">
	<div class="cold-md-12">
		<fieldset class="fsStyle">			
			<legend class="legendStyle">Fechas (desde,hasta)</legend>

			<div class="row" id="demo">

		    <div class="col-md-6">
		      <?php $date = date('Y-m-d'); ?>						
		      <input type="text" value="{{ $date }}" name="fecha_desde" class="form-control input-sm datepicker no_br flat text-center">  

		    </div>

		    <div class="col-md-6">
		      <?php $date = date('Y-m-d'); ?>
		      <input type="text" value="{{ $date }}" name="fecha_hasta" class="form-control input-sm datepicker no_br flat text-center">  
		    </div>
			
			</div>									

	  </fieldset>
	</div>
</div>
<!-- Articulo -->	

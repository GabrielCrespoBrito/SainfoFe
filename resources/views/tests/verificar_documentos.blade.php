@view_data([
	'layout' => 'layouts.master' , 
	'title'  => 'Verificar documentos',
	'titulo_pagina'  => 'Verificar Docuentos',	
	'bread'  => [ ['Texto','Link'] , ['Texto2'] ],
])

@php
	$empresa = get_empresa();
@endphp


@slot('contenido')

	<form method="post" action="{{ route('test.verificar_documentos') }}">
		@csrf

		<div class="row">

			<div class="form-group col-md-4">  
		    <div class="input-group">
		      <span class="input-group-addon">Ruc Empresa </span>
		        <input class="form-control input-sm" required="required" name="ruc_empresa" type="text" value="{{ $empresa->EmpLin1 }}">     
		    </div>
	  	</div>

		<div class="form-group col-md-4">  
		    <div class="input-group">
		      <span class="input-group-addon"> Usuario Sol </span>
		        <input class="form-control input-sm" required="required" name="usuario_sol" type="text" value="{{ $empresa->FE_USUNAT }}">     
		    </div>
	  	</div>

		<div class="form-group col-md-4">  
		    <div class="input-group">
		      <span class="input-group-addon">Clave Sol </span>
		        <input class="form-control input-sm" required="required" name="clave_sol" type="text" value="{{ $empresa->FE_UCLAVE }}">     
		    </div>
	  	</div>	  	

  	</div>

  	<div class="row">

			<div class="form-group col-md-4">  
		    <div class="input-group">
		      <span class="input-group-addon">Tipo Documento </span>
		        <select class="form-control input-sm" required="required" name="tipo_documento">     
		        	<option value="01"> Factura </option>
		        	<option value="03"> Boleta </option>
		        	<option value="07"> Nota de credito </option>
		        	<option value="08"> Nota de debito </option>
		        </select>
		    </div>
	  	</div>

			<div class="form-group col-md-4">  
		    <div class="input-group">
		      <span class="input-group-addon"> Serie </span>
		        <input class="form-control input-sm" required="required" name="serie_documento" type="text" value="F001">     
		    </div>
	  	</div>

			<div class="form-group col-md-4">  
		    <div class="input-group">
		      <span class="input-group-addon"> Numero </span>
		        <input class="form-control input-sm" required="required" name="numero" type="text" value="000001">     
		    </div>
	  	</div>	  	

  	</div>

  	<div class="row">
  		<div class="col-md-12">	
  			<button type="submit"> Enviar </button>
  		</div>
  	</div>

	</form>


	<form method="post" action="{{ route('test.verificar_ticket') }}">
		@csrf

		<div class="row">

			<div class="form-group col-md-4">  
		    <div class="input-group">
		      <span class="input-group-addon">Ruc Empresa </span>
		        <input class="form-control input-sm" required="required" name="ruc_empresa" type="text" value="{{ $empresa->EmpLin1 }}">     
		    </div>
	  	</div>

		<div class="form-group col-md-4">  
		    <div class="input-group">
		      <span class="input-group-addon"> Usuario Sol </span>
		        <input class="form-control input-sm" required="required" name="usuario_sol" type="text" value="{{ $empresa->FE_USUNAT }}">     
		    </div>
	  	</div>

		<div class="form-group col-md-4">  
		    <div class="input-group">
		      <span class="input-group-addon">Clave Sol </span>
		        <input class="form-control input-sm" required="required" name="clave_sol" type="text" value="{{ $empresa->FE_UCLAVE }}">     
		    </div>
	  	</div>	  	

  	</div>

  	<div class="row">

			<div class="form-group col-md-12">  
		    <div class="input-group">
		      <span class="input-group-addon"> Ticket </span>
		        <input class="form-control input-sm" required="required" name="ticket" type="text" value="">     
		    </div>
	  	</div>

  	</div>

  	<div class="row">
  		<div class="col-md-12">	
  			<button type="submit"> Enviar </button>
  		</div>
  	</div>

	</form>
	

@endslot


@endview_data




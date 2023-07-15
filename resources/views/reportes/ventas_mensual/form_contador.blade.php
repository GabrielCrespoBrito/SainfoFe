@extends('layouts.master_contador')

<?php set_timezone(); ?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   

@endsection

@section('js')
  <script type="text/javascript">
		$(document).ready(function(){
			$(".buscar").on('click', function(){
				let url = $("[name=year] option:selected").attr('data-link');
				$(this).attr('href' , url );
			})
		});
  </script>
@endsection

@section('titulo_pagina', 'Reporte mensual de ventas')

@section('contenido')

@php $class_adicional = "reportes"; @endphp

  <form method="get" action="{{ route('reportes.ventas_mensual_pdf') }}" target="_blank">

	<!-- Fechas -->
	<div class="filtro" id="condicion">
		<div class="cold-md-12">
			<fieldset class="fsStyle">			
				<legend class="legendStyle">Mes</legend>
				<div class="row" id="demo">
			    <div class="col-md-12">
			    	@component('components.specific.select_mes')
			    	@endcomponent
			    </div>
				</div>									
		  </fieldset>
		</div>
	</div>
	<!-- Fechas -->	

  <div class="row">
    <div class="col-md-12">
      <input type="submit" name="Vista" value="Enviar" class="btn btn-default">
    </div>
  </div>

  </form>


@endsection
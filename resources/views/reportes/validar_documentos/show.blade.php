@extends('layouts.master')

<?php 
	$formato = $formato ?? null;
	$mes = $mes ?? null;
	set_timezone();
?>

@section('css')  
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"/>   
@endsection

@section('titulo_pagina', 'Consultar validez de documentos sunat' )

@section('contenido')

	@php $class_adicional = "reportes"; @endphp



  <form method="get" {{ (int) $report }} action="{{ route('reportes.validate_documentos_mensual_report') }}" target="_blank">



	<!-- Fechas -->
	<div class="row">

	{{-- Mes --}}
	<div class="col-md-12">

		<div class="filtro" id="condicion">
				<fieldset class="fsStyle">			
					<legend class="legendStyle">Mes </legend>
					<div class="row" id="demo">
						<div class="col-md-12">
							@component('components.specific.select_mes', ['mes' => $mes])	@endcomponent
						</div>
					</div>									
				</fieldset>
		</div>
	</div>
	{{-- Mes --}}

	</div>		
	<!-- Fechas -->	

	{{-- estado_sunat --}}

  <div class="row">
    <div class="col-md-12 text-center">
      <input type="submit" name="Vista" value="Generar Reporte" class="btn btn-primary">
    </div>
  </div>

  </form>

	@if( $report )

			

		@if( $success )
		<div style="margin-top:20px" class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>Acciò exitosa!</strong> No hay problemas en los documentos
		</div>

		@else

		<div style="margin-top:20px" class="alert alert-warning alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>Se requieren acciones</strong> Hay documentos que requiren acciones
		</div>

		@endif



	@if( ! $success )

  <div class="row" id="div_info_reporte">
    <div class="col-md-12">
			<h3 class="title_reporte"> Información Reporte </h3>
			@include('reportes.validar_documentos.partials.table_error' , ['docs' => $docs])
    </div>
  </div>

	@endif
	@endif

@endsection
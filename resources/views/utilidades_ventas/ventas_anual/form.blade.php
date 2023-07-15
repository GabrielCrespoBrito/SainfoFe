@extends('layouts.master')
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

@section('titulo_pagina', 'Ventas por año')

@section('contenido')

@php $class_adicional = "reportes"; @endphp

@include('reportes.partials.botones' , ['target' => '_blank'])


@include('reportes.ventas_anual.partials.filtros')

@endsection
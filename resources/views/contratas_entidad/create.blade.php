@extends('layouts.master')

@section('bread')
<li>  <a href="{{ route('contratas.index') }}"> Generar Contrato </a> </li>
<li> Crear </li>

@endsection

@add_assets(['libs' => ['froala_editor','select2' , 'datepicker'] , 'js' => ['helpers.js','empresa/contrata_entidad.js'] ])
@endadd_assets

@section('titulo_pagina', 'Crear documento')

@section('contenido')
	@include('contratas_entidad.partials.form', [ 'accion' => "create" ])
@endsection


@extends('layouts.master')

@section('bread')
<li>  <a href="{{ route('contratas.index') }}"> Contratas </a> </li>
<li> Crear </li>

@endsection

@add_assets(['libs' => ['ckeditor5'] , 'js' => ['helpers.js','empresa/contrata.js'] ])
@endadd_assets


@section('titulo_pagina', 'Crear documento')

@section('contenido')

	<div class="form-group">
		{{ Form::label('Nombre') }}
		{{ Form::text('nombre' , null , ['class' => 'form-control']) }}
	</div>

	@method('POST')

	<main>
		<div class="document-editor">
			<div class="toolbar-container"></div>
			<div class="content-container">
				<div id="contenido"></div>
			</div>
		</div>
	</main>

	<div class="form-group" style="margin-top:20px">
		<a href="#" id="crear" class="btn btn-success" data-url="{{ route('contratas.store') }}" > Guardar </a>
		<a href="{{ route('contratas.index') }}" class="btn btn-danger"> Volver </a>
	</div>

@endsection


@extends('layouts.master')

@section('bread')
<li>  <a href="{{ route('contratas.index') }}"> Contratas </a> </li>
<li> {{ $contrata->nombre }} </li> 
<li> Modificar </li> 

@endsection

@add_assets(['libs' => ['ckeditor5'] , 'js' => ['helpers.js','empresa/contrata.js'] ])
@endadd_assets


@section('titulo_pagina', $contrata->nombre )

@section('contenido')

	<div class="form-group">
		{{ Form::label('Nombre') }}
		{{ Form::text('nombre' , $contrata->nombre , ['class' => 'form-control' ]) }}
	</div>

	@method('PUT')

{{-- 	<form style="margin-top: 30px;">
		<textarea id="contenido" name="contenido" rows="10" cols="80">
  		{!! $contrata->contenido !!}
		</textarea>
	</form>
 --}}

	<main>
		<div class="document-editor">
			<div class="toolbar-container"></div>
			<div class="content-container">
				<div id="contenido">
					{!! $contrata->contenido !!}
				</div>
			</div>
		</div>
	</main>




	<div class="form-group" style="margin-top:20px">

		<a href="#" id="crear" data-url="{{ route('contratas.update', $contrata->id) }}" class="btn btn-success"> Actualizar </a>

		<a href="{{ route('contratas.index') }}" class="btn btn-danger volver"> Volver </a>

	</div>

@endsection


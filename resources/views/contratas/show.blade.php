@extends('layouts.master')

@section('bread')
<li>  <a href="{{ route('contratas.index') }}"> Contratas </a> </li>
<li> {{ $contrata->nombre }} </li> 
@endsection

@add_assets(['libs' => ['froala_editor'] , 'js' => ['helpers.js','empresa/contrata.js'] ])
@endadd_assets


@section('titulo_pagina', $contrata->nombre )

@section('contenido')

	<div class="form-group">
		{{ Form::label('Nombre') }}
		{{ Form::text('nombre' , $contrata->nombre , ['class' => 'form-control', 'readonly' => 'readonly' ]) }}
	</div>

	<div class="row">

		<div class="form-group col-md-6">
			{{ Form::label('Fecha creación') }}
			{{ Form::text('nombre' , $contrata->created_at , ['class' => 'form-control', 'readonly' => 'readonly' ]) }}
		</div>

		<div class="form-group col-md-6">
			{{ Form::label('Fecha Modificación') }}
			{{ Form::text('nombre' , $contrata->updated_at , ['class' => 'form-control', 'readonly' => 'readonly' ]) }}
		</div>	

	</div>

	@method('POST')



	<p> Contenido: <a href="{{ route('contratas.pdf' , $contrata->id) }}" class="btn btn-default btn-xs"> <span class="fa fa-pdf-o"></span> Ver en pdf </a>  </p>

	<hr>

  <div style="margin-top: 30px;">
  	
  	{!! $contrata->contenido !!}

  </div>



	<div class="form-group" style="margin-top:20px">


		<a href="{{ route('contratas.edit', $contrata->id) }}" class="btn btn-success"> Editar </a>

		<a href="{{ route('contratas.index') }}" class="btn btn-danger"> Volver </a>

	</div>

@endsection


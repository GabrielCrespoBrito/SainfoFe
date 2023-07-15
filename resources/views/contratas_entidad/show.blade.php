@extends('layouts.master')

@section('bread')
<li>  <a href="{{ route('contratas_entidad.index') }}"> Documentos </a> </li>
<li> Ver </li> 
@endsection

@add_assets(['libs' => ['froala_editor'] , 'js' => ['helpers.js','empresa/contrata.js'] ])
@endadd_assets


@section('titulo_pagina', "Ver" )

@section('contenido')
	
	@include('contratas_entidad.partials.form', [ 'accion' => "show" ])


@endsection


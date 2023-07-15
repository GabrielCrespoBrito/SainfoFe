@extends('layouts.master')

@section('bread')
<li>  <a href="{{ route('contratas_entidad.index') }}"> Contratas Cliente </a> </li>
<li> Modificar </li> 

@endsection

@add_assets(['libs' => ['ckeditor5'] , 'js' => ['helpers.js','empresa/contrata.js'] ])
@endadd_assets


@section('titulo_pagina', "Modificar documento" )

@section('contenido')

	@include('contratas_entidad.partials.form', [ 'accion' => "edit" ])	

@endsection


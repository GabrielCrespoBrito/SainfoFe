@extends('layouts.master')

@section('js')

<script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
<script src="{{ asset('js/helper.js') }}"></script>
<script src="{{ asset('js/empresa/empresa.js') }}"> </script>

<script type="text/javascript">
	var error = [];
	@if($errors->count())			
		@foreach( $errors->all() as $error )
			error.push( "{{ $error }}" );
		@endforeach
	notificaciones(error, "danger");
	@endif
	@if( session()->has('message')){
		notificaciones("{{session()->get('message') }}" , "success");
	}
	@endif
</script>
@endsection
@section('titulo_pagina', 'Parametro Empresa')

@section('contenido')


<!-- Tab -->
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Información principal </a></li>
		<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Parametros</a></li>	
	</ul>

	<!-- .tab-content -->
	<div class="tab-content">

		<!-- .tab-pane -->
		<div class="tab-pane active" id="tab_1">
			@include('empresa.partials.principal.form') 
		</div>
		<!-- /.tab-pane -->

		<!-- .tab-pane -->
		<div class="tab-pane" id="tab_2">
			@include('empresa.partials.parametros.form')
		</div>
		<!-- /.tab-pane -->

	</div>
	<!-- /.tab-content -->

</div>
<!-- Tab -->


@endsection
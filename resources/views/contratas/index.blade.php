@extends('layouts.master')

@add_assets(['js' => ['helpers.js']  ])

@endadd_assets

@push('js')

<script type="text/javascript">
	
	$(document).ready(function(){

		Helper.init();
		$(".sendemail").on('click', function(){
			$("#modalEnvioCorreo").modal();
		});
		
	})
	
</script>	

@endpush


@section('bread')
	<li> Contratas </li>
@endsection

@section('titulo_pagina', 'Contratas')

@section('contenido')

<div class="acciones-div">
  <a href="{{ route('contratas.create') }}" class="btn btn-primary btn-flat pull-right crear-nuevo-usuario"> <span class="fa fa-plus"></span> Nuevo </a>
</div>


@component('components.table', ['thead' => [ '#' , 'Nombre' , 'Contenido' , 'Acciones'] ])
	
	@slot('body')

		@foreach( $contratas as $contrata )

			<tr data-id="{{ $contrata->id }}">
				<td> {{ $contrata->id }} </td>
				<td> {{ $contrata->nombre }} </td>
				<td> <a href="{{ route('contratas.pdf' , $contrata->id )  }}" target="_blank" class="btn btn-xs btn-default btn-xs"> <span class="fa fa-eye"></span> Ver</a> </td>
				<td> 
					@include('partials.column_accion', ['links' => 
					[['src' => route('contratas.show' , $contrata->id ) , 'texto' => 'Ver' ],
					['src' => route('contratas.edit', $contrata->id) , 'texto' => 'Editar' ],
					['src' => route('contratas_entidad.create') , 'texto' => 'Generar documento' ],			
					['src' => "#" , 'texto' => 'Eliminar' , 'class' => 'eliminate-element' , 'id' => $contrata->id  ]],
					['src' => "#" , 'texto' => 'Enviar por correo' , 'class' => 'sendemail' ],			
					])
				</td>		
			</tr>			

		@endforeach

	@endslot

@endcomponent


@include('partials.modal_eliminate', ['url' => route('contratas.destroy' , 'XX') ])

@endsection
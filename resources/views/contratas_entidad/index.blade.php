@extends('layouts.master')

@add_assets(['js' => ['helpers.js' , 'components/modal_correo.js' ]  ])
@endadd_assets

@push('js')

<script type="text/javascript">

	$(document).ready(function(){		
		AppEnvioCorreo.init();
		$(".send-email").on('click' , function(){
			let $t = $(this)
			AppEnvioCorreo.set_id( $t.attr('data-id') );
			AppEnvioCorreo.show( $t.parents('tr').data('info')  );
		})
	})

</script>	

@endpush

@section('bread')
	<li> Contratas </li>
@endsection

@section('titulo_pagina', 'Contratos Generados')

@section('contenido')

<div class="acciones-div">
  <a href="{{ route('contratas_entidad.create') }}" class="btn btn-primary btn-flat pull-right crear-nuevo-usuario"> <span class="fa fa-plus"></span> Nuevo </a>
</div>


@component('components.table', ['thead' => [ '#' , 'Tipo' , 'Nombre', 'Contenido' , 'Acciones'] ])
	
	@slot('body')

		@foreach( $contratas as $contrata )

			<tr data-info="{{ $contrata->getModel() }}">
				<td> {{ $contrata->id }} </td>
				<td> {{ strtoupper($contrata->tipo()) }} </td>
				<td> {{ $contrata->nameEntidad() }} </td>

				<td> <a href="{{ route('contratas_entidad.pdf' , $contrata->id )  }}" target="_blank" class="btn btn-xs btn-default btn-xs"> <span class="fa fa-eye"></span> Ver</a> </td>
				<td> 
					@include('partials.column_accion', ['links' => 
					[['src' => route('contratas_entidad.show' , $contrata->id ) , 'texto' => 'Ver' ],
					['src' => route('contratas_entidad.edit', $contrata->id) , 'texto' => 'Editar' ],
					['src' => "#" , 'texto' => 'Eliminar' , 'class' => 'eliminate-element' , 'id' => $contrata->id  ],
					['src' => "#" , 'texto' => 'Enviar por correo' , 'class' => 'send-email' , 'id' => $contrata->id ]
				]])

				</td>		
			</tr>			

		@endforeach

	@endslot

@endcomponent

@include('partials.modal_eliminate', ['url' => route('contratas_entidad.destroy' , 'XX') ])	

@include('partials.modal_enviar_correo', [ 'asunto' => 'Enviar documento' , 'url' => route('contratas_entidad.send_email' , 'XX') ])


@endsection
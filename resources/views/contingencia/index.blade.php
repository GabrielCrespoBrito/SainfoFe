@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Resumen de contingencia',
  'titulo_pagina'  => 'Resumen de contingencia', 
  'bread'  => [ ['Resumen de contingencia'] ],
  'assets' => ['js' => ['helpers.js']]
])

@push('js')
	<script type="text/javascript">
		$(document).ready(function(){
			Helper.init();
		})
	</script>
@endpush

@slot('contenido')

	<div class="row">
		<div class="acciones-div col-md-12">
			<a  class="btn btn-flat btn-primary pull-right" href="{{ route('contingencia.create') }}"> <span class="fa fa-plus"></span> Nueva </a>
		</div>
	</div>  

	@component('components.table', [ 'thead' => [ 'N° Operacion', 'Fecha documento', 'Fecha emisión', 'Ticket', 'Txt', 'Acciones'] , 'id' => 'details' , 'data-url' => '' ])

		@slot('body')

			@foreach( $contingencias as $contingencia )
			<tr>
				<td> {{ $contingencia->docnume }} </td>
				<td> {{ $contingencia->fecha_documento }} </td>
				<td> {{ $contingencia->fecha_emision }} </td>
				<td> {{ $contingencia->ticket }} </td>
				<td>
					<a class="btn btn-flat btn-default btn-xs" href="{{ route('contingencia.txt', $contingencia->id ) }}"> 
						<span class="fa fa-file-text-o"></span> Archivo
					 </a>					
				</td>

				<td>
					@php
					$links = [
						['texto' => 'Ver', 'src' => route('contingencia.show', $contingencia->id)]
					];

					if( ! $contingencia->hasTicket() ){
						$links[] = ['texto' => 'Modificar', 'src' => route('contingencia.edit', $contingencia->id)];
						$links[] = ['texto' => 'Eliminar', 'src' => '#' , 'class' => 'eliminate-element' , 'id' => $contingencia->id ];
					}

					@endphp

					@component('partials.column_accion', ['links' => $links ])
					@endcomponent
				</td>


			</tr>
			@endforeach

	  @endslot

	  @endcomponent



@include('partials.modal_eliminate', ['url' => route('contingencia.destroy' , 'XX') ])	

@endslot  

@endview_data
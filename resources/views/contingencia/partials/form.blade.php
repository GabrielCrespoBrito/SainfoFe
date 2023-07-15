@php
	$route = $action == "create" ? ['contingencia.store'] : ['contingencia.update' , 'id' => $contingencia->id ];
@endphp


{!! Form::open([ 'route' => $route , 'method' => 'POST' , 'class' => 'form-contingencia']) !!}

	<div class="row">
		<div class="col-md-12 acciones-div">
			<div class="">
				@if($action != "create")

					<a class="btn btn-flat btn-default pull-left" href="{{ route('contingencia.txt', $contingencia->id ) }}"> 
						<span class="fa fa-file-text-o"></span> Archivo
					 </a>
				@endif

				<a class="btn btn-flat btn-danger pull-right" id="salir" href="{{ route('contingencia.index') }}"> Salir  </a>
				@if( $show )
					@if( ! $contingencia->hasTicket() )
						<a class="btn btn-flat btn-primary pull-right" href="{{ route('contingencia.edit', $contingencia->id) }}"> Modificar</a>
					@endif
				@else
					<a class="btn btn-flat btn-primary pull-right save-form" href="#"> Guardar  </a>
				@endif
			</div>
		</div>
	</div>

	@if( ! $show )
	<div class="motivos hide">
		<select class="form-control input-sm" name="motivos">
			@foreach( $motivos as $motivo )
				<option value="{{ $motivo->id }}"> {{ $motivo->descripcion }} </option>
			@endforeach
		</select>
	</div>
	@endif


<div class="row">
	<div class="form-group col-md-6">
			{{ Form::label( "Fecha Documento" , null, ['class' => 'col-md-4']) }}
			<div class="col-md-8">
				@php
					$attributes = ['class' => 'form-control datepicker' , 'required' => 'required' ];
					if( $action != "create" )	$attributes = array_merge( $attributes , ['disabled' => 'disabled' ]  );
				@endphp
				{{ Form::text('fecha_documento', $contingencia->fecha_documento , $attributes ) }}
			</div>
	</div>

	<div class="form-group col-md-6">
		{{ Form::label( "Fecha Emisión" , null, ['class' => 'col-md-4']) }}
		<div class="col-md-8">
			{{ Form::text('fecha_emision', $contingencia->fecha_emision , [ 'class' => 'form-control' , 'disabled' => 'disabled' ] ) }}
		</div>
	</div>
</div>

<div class="row">

	<div class="form-group col-md-6">
			{{ Form::label( "Nro Operación" , null, ['class' => 'col-md-4']) }}
			<div class="col-md-8">				
				{{ Form::text('numero', $contingencia->docnume, ['class' => 'form-control' , 'disabled' => 'disabled'] ) }}
			</div>
	</div>

	<div class="form-group col-md-6">
		{{ Form::label( "Ticket" , null, ['class' => 'col-md-3']) }}
		<div class="col-md-9">
			{{-- @dd( $action , $show ) --}}
			@php
				$attributes = ['class' => 'form-control' ];
				if( $show || $action == "create" )	$attributes = array_merge( $attributes , ['disabled' => 'disabled']  );
			@endphp
			{{ Form::text('ticket', $contingencia->ticket , $attributes ) }}
		</div>
	</div>

</div>

@if(! $show )
<div class="row filtro">
  <div class="col-md-2"> Filtrar: </div>
    <div class="col-md-10">
      <a href="#" class="btn btn-sm btn-flat btn-default pull-right delete"> <span class="fa fa-trash"></span>  Quitar</a>
      <a href="#" class="btn btn-sm btn-flat btn-default pull-right add" data-url="{{ route('contingencia.addItems') }}"> <span class="fa fa-search"></span>  Agregar </a>
    </div>
</div>
@endif

	@component('components.table', [ 'thead' => [ 'VtaOper', 'TD', 'Serie', 'N° Doc', 'Gravada', 'Exonerada', 'Inafecta', 'IGV', 'ISC' ,'Motivo'] , 'id' => 'details' , 'data-url' => '' ])


		@if( $contingencia->detalles->count() )
			@slot('body')
				@foreach( $contingencia->detalles as $detalle )
				<tr>
					<td> {{ $detalle->vtaoper }} </td>
					<td> {{ $detalle->tidcodi }} </td>
					<td> {{ $detalle->serie }} </td>
					<td> {{ $detalle->numero }} </td>
					<td> {{ $detalle->gravada }} </td>
					<td> {{ $detalle->exonerada }} </td>
					<td> {{ $detalle->inafecta }} </td>
					<td> {{ $detalle->igv }} </td>
					<td> {{ $detalle->isc }} </td>
					<td> 
						@if($show)
							{{ $detalle->motivo->descripcion }} </td>
						@else

							<select class="form-control input-sm" name="motivos">
								@foreach( $motivos as $motivo )
									<option {{ $motivo->id == $detalle->motivo_id ? 'selected=selected' : ''  }} value="{{ $motivo->id }}"> {{ $motivo->descripcion }} </option>
								@endforeach
							</select>

						@endif
				</tr>
				@endforeach
			@endslot
		@endif

	@endcomponent



{{ Form::close() }}
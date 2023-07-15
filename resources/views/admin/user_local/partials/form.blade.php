@php
	$isCreate = $action == "create";
	$route = 'admin.user-local.store';
	$method = "post";
	$attributes = ['class' => 'form-control'];

	if( ! $isCreate ){
		$method = "put";
		$route = ['admin.user-local.update', [ 'usucodi' => $userlocal->usucodi, 'loccodi' => $userlocal->loccodi]];
		$route = ['admin.user-local.update', $userlocal->usucodi, $userlocal->loccodi ];
		$attributes['disabled'] = 'disabled';
	}

@endphp

{!! Form::open(['method' => $method , 'route' => $route ]) !!}

  @include('admin.partials.empresa_input')

	<div class="col-md-12">
		@if( $errors->count() )
			@foreach( $errors->all() as $error )
				@if( strpos("SQLSTATE[23000]", $error) !== false )
					@continue
				@endif
				<div class="alert alert-danger alert-dismissible">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				  {{ $error }}
				</div>
			@endforeach
		@endif
	</div>

	<div class="row">

		<div class="col-md-12">
			@php
				$attribute = [];
			@endphp
			{!! Form::label('Usuario')  !!}
			{!! Form::select('usucodi', $users->pluck('usulogi','usucodi') , $userlocal->usucodi , $attributes  )  !!}
		</div>

		<div class="col-md-12">
			{!! Form::label('Local')  !!}
			{!! Form::select('loccodi', $locales->pluck('LocNomb','LocCodi') , $userlocal->loccodi , [ 'class' => 'form-control' ]  )  !!}
		</div>

		<div class="col-md-12" style="margin-top: 10px">
			{!! Form::submit('Guardar', ['class' => 'btn btn-primary btn-flat']) !!}

      <a href="{{ route('admin.user-local.index') }}" class="btn btn-danger btn-flat"> Cancelar </a>


		</div>

	</div>

{!! Form::close() !!}

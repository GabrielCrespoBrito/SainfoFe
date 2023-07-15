
<form id="form-export" action="{{ route('export.excell.generate') }}" method="GET" target="_blank">

	<div class="row">
		<div class="form-group col-md-4">
		  <label>Para </label>
			{!! Form::select('para', $para , null, ['class' => 'form-control'] ) !!}
		</select>
		</div>

		<div class="form-group col-md-4">
		  <label>Tipo </label>
			{!! Form::select('tipo', $tipo , null, ['class' => 'form-control'] ) !!}
		</select>
		</div>

		<div class="form-group col-md-4">
		  <label>Periodo </label>
			{!! Form::select('periodo', $meses , null, ['class' => 'form-control'] ) !!}
		</select>
		</div>

	</div>	


	<div class="row">
		<div class="col-md-12">
			<button type="submit" class="btn btn-success submit-buttom btn-flat"> Generar </button>
			@include('partials.button_salir')
		</div>
	</div>

</form>
@if( $model->isSalida() )

@if( !$model->isTrasladoPendiente() )
  <a href="{{ route('guia.edit', $model->CtoOper ) }}" target="_blank" class="btn btn-xs btn-flat btn-default">
    <span class="fa fa-truck"></span> {{ $model->CtoOper }}
  </a>
@endif

@else
  <a href="{{ route('guia.edit', $model->cpaOper ) }}" target="_blank" class="btn btn-xs btn-flat btn-default">
    <span class="fa fa-truck"></span> {{ $model->cpaOper }}
  </a>
@endif
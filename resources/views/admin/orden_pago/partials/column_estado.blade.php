<span 
  class="btn btn-xs btn-flat {{ $model->isPendiente() ? 'btn-default': 'btn-success' }}"> 
  <span class="fa {{ $model->isPendiente() ? 'fa-spinner spinner fa-spin' : 'fa-check' }}"> </span>
  {{ ucfirst($model->estatus) }}
</span>

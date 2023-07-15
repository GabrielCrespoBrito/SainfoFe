<a 
  href="#" 
  class="edit-btn plan-link {{ $model->isMaestro() ? 'is-maestro' : 'is-empresa' }}"
  data-url="{{ route('admin.plan.edit', $model->id ) }}"
>
 {{ $model->codigo }} 
 </a>

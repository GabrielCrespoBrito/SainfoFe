<form action="{{ route('admin.plan.update', $plan->id ) }}" method="post" id="form-principal">
  @csrf
  @foreach( $plan->caracteristicas as $caracteristica )
    @include('admin.planes.partials.form_caracteristica', [
      'isConsumo' => $caracteristica->isConsumo(),
      'isMaestro' => $caracteristica->isMaestro(),
      'canModify' => $plan->isMaestro() 
    ])
  @endforeach

</form>
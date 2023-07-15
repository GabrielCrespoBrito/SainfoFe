@component('components.modal', ['id' => 'modalTerminos', 'title' => 'Terminos y condiciones' ])
  @slot('body')
    @foreach( $condiciones as $condicion )
      <p> {{ $condicion }} </p>
    @endforeach

  @endslot
@endcomponent
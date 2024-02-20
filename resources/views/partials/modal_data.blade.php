{{-- Modal para poner información --}}
@php
  $size =  $size ?? 'modal-md';
@endphp


@component('components.modal', ['id' => 'modalData' , 'size' => $size, 'title' => '' ])
  @slot('body')
  @endslot
@endcomponent 
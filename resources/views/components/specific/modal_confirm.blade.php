<?php
  $id = $id ?? "modalConfirm";
  $title = $title ?? "Confirmacion";
  $size = $size ?? "modal-md";
  $body = $body ?? "";
?>

@component('components.modal', [ 'id' => $id, 'title' => $title, 'size' => $size ])
  @slot('body')
    {!! $body !!}
  @endslot
@endcomponent
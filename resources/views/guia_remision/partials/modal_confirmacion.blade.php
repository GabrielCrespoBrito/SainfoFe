@php
  $despachoBtn = $despachoBtn ?? true;
@endphp

@component('components.modal', [ 'id' => 'modalConfirmacion', 'size' => 'modal-sm', 'title' => 'Confirmar guardado' ])
@slot('body')

@if( $accion == "create" && $despachoBtn)
<div>
<label for="makeDespacho"> <input id="makeDespacho" name="despachar" value="1" type="checkbox">  Realizar despacho </label> 
<br> <br>
</div>

@endif
<a href="#" class="btn btn-primary btn-flat acept_confirmation" data-id="save"> Guardar </a>
@endslot
@endcomponent
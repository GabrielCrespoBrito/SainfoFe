@php 
  $name = isset($name) ? $name : "registro";
  $title = "Esta seguro de eliminar este {$name}";
@endphp

@component('components.modal', ['id' => 'modalEliminate' , 'size' => 'modal-sm' , 'title' => $title ])
  @slot('body')
	  <form id="formEliminate" style="" method="post" data-base-action="{{ $url }}" action="{{ $url }}">  
	    @csrf
	    @method('DELETE')
	    <button class="btn btn-default"> Aceptar </button>
	  </form>
  @endslot

@endcomponent 
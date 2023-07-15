@php
  $route = $route ?? null;
  $class_name = $class_name ?? null;
@endphp

<div class="row">
	<div class="col-md-12">
		<a class="btn btn-primary pull-right {{ $class_name }}" data-route="{{ $route }}" href="{{ $link }}"> <span class="fa fa-plus"></span>  Nuevo </a>
	</div>
</div>
@php
	$className = $className ?? 'block_elemento';
	$id = $id ?? '';
	$text = $text ?? '';

@endphp


<div id="{{ $id }}" class="{{ $className }}" style="display: none">
	<div class="cargando">
		
		<div class="div-spin">
			<span class="fa fa-spin fa-spinner"></span> 
		</div>
		
		@if($text)
		<div class="div-text">
			<span class="text"> {{ $text }} </span> 
		</div> 
		
		@endif
	</div>
</div>
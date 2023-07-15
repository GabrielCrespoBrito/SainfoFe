@php
	$href = $href ?? route('home');
	$className =  $className ?? 'btn-danger';
	$className .= ' ' . ($pull ?? 'pull-right');	
	$text = $text ?? __('messages.exit');

@endphp

@include('partials.button', compact('href','className','text'))
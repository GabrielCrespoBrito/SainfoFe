<div class="dropdown">
  <button class="btn btn-xs btn-default dropdown-toggle {{ $class_btn ?? '' }}" type="button" data-toggle="dropdown"> {{ isset($nombre) ? $nombre : 'Acciones'  }}  <span class="caret"></span>
  </button>
  <ul class="dropdown-menu sainfo">
		@foreach($links as $a => $link )
			<li> 
				<a
				{!! isset($link['attributes']) ? attributes($attributes) : '' !!} 
				data-id="{{ $link['id'] ?? '' }}" 
				class="{{ $link['class'] ?? '' }}" 
				href="{{ $link['src'] }}" 
        @isset($link['data-codigo']) data-codigo="{{ $link['data-codigo'] }}" @endisset
        @isset($link['data-tipo']) data-tipo="{{ $link['data-tipo'] }}" @endisset
				target="{{ $link['target'] ?? '_self' }}">  
					{!! $link['texto'] !!} 
				</a> 
			</li>
		@endforeach		
  </ul>
</div>
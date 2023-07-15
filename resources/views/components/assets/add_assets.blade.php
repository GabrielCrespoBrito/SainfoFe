@if( isset($libs) )
@foreach( $libs as $libreria )
	@php 	
		$pathsGroup = get_assets($libreria);
	@endphp
	@foreach( $pathsGroup as $key => $pathGroup )
		@if( $pathGroup['has'] )
			@push($key)
				{!! $pathGroup['paths'] !!}
			@endpush
		@endif
	@endforeach
@endforeach
@endif


@if( isset($js) )
	@foreach( $js as $j )
		@php 	
			$path = get_asset_js($j);
		@endphp
		@push('js')
			{!! $path !!}
		@endpush
	@endforeach
@endif
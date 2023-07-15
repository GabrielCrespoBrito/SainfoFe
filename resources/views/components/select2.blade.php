@php
  $onlyShow = isset($onlyShow);
@endphp

<div class="fixed_position">
  
  @if( $onlyShow )
    {{-- <select  --}}
    {{-- data-url="{{ $url }}"  --}}
    {{-- data-id="{{ isset($id) ? $id : '' }}"  --}}
    {{-- data-text="{{ isset($text) ? $text : '' }}"  --}}
    {{-- id="{{ isset($id) ? $id : '' }}"  --}}
    {{-- name="{{ $name }}"  --}}
    {{-- data-text="{{ isset($text) ? $text : '' }}"  --}}
    {{-- class="form-control {{ isset($size) ? $size : 'input-sm' }} select2"   --}}
    {{-- </select> --}}
  @else 

    <select 
    data-url="{{ $url }}" 
    data-value="{{ isset($value) && isset($text) ? $value : '' }}" 
    data-text="{{ isset($text) ? $text : '' }}" 
    data-id="{{ isset($data_id) ? $data_id : '' }}" 
    id="{{ isset($id) ? $id : '' }}" 
    name="{{ $name }}" 
    class="form-control {{ isset($size) ? $size : 'input-sm' }} select2" style="position:absolute;">  
    </select>

  @endif

</div>
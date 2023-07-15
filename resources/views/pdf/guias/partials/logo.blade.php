@php
  $img_logo_class = $img_logo_class ?? "";
  $class_name = $class_name ?? "";
  $width = $width ?? '';
@endphp

<div class="logo {{ $class_name }}">

  <img 
  style=""
  class="img-logo {{ $img_logo_class }}"
  width="{{ $width }}"
  src="data:image/png;base64,{{ $logoDocumento }}">

</div>
@php
  $href = $href ?? "#";
  $className = $className ?? "btn-default";
  $text = $text ?? "";
@endphp

<a href="{{ $href }}" class="btn {{ $className }}"> {!! $text !!} </a>
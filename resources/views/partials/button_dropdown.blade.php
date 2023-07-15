@php
  $name = $name ?? '';
  $className = $className ?? '';
@endphp

<div class="btn-group">
<button type="button" class="btn btn-default btn-flat {{ $className }}"> <span class="fa fa-file-text-o"></span> {{ $name }} </button>
<button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
<span class="caret"></span>
<span class="sr-only">Toggle Dropdown</span>
</button>
<ul class="dropdown-menu" role="menu">
  @foreach( $options as $option )
    <li><a class="{{ $option['class'] ?? '' }}"  target="{{ $option['target'] ?? 'self' }}" href="{{ $option['route'] ?? '#' }}">{{ $option['text'] }}</a></li>
  @endforeach
</ul>
</div>
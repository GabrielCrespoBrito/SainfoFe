@php
  $color = $color ?? 'info';
  $cancel = $cancel ?? true;
  $title = $title ?? false;
  $icon = $icon ?? 'info';
  $message = $message ?? '';
@endphp

<div class="alert alert-{{ $color }} {{ $cancel ? 'alert-dismissible' : '' }}">

  @if($cancel)
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  @endif

  @if($title)
    <h4><i class="icon fa fa-{{ $icon }}"></i> {{ $title }}</h4>
  @endif

  {!! $message !!}
  
</div>
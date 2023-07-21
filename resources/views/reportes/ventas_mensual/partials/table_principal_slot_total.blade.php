@php
  $bg = $bg ?? false;
  $strong = $strong ?? false;
@endphp

<div class="row" style="{{ $bg ? "background-color:#e2e2e2": "" }}">
  <div class="col-md-6 text-left {{ $strong  ? 'strong' : ''}}">
    {{ $nombre }}
  </div>
  <div class="col-md-6 text-right {{ $strong  ? 'strong' : ''}}">
    <span style="padding-right:20px;"> {{ $total }}</span>
  </div>
</div>


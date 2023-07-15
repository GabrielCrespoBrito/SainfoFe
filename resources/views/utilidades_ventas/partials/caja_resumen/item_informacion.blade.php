@php
  $className =  $className ?? '';
@endphp

<div class="col-md-6 data-venta {{ $className }}" style="">
  <div class="input-group">
    <div class="input-group-addon">{{ $nombre }}:</div>

    <p type="text" class="form-control no_p"> 

      <span class="info pull-left"> <span class="name">S./</span> <span class="value"> {{ $soles }}</span> </span>
      <span class="info pull-right"> <span class="name">US./</span> <span class="value"> {{ $dolar }}</span> </span>
  </p>
  </div>
</div>
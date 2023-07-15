
@php
$active = $active ?? false;
$nombreMoneda = $moneda == '01' ? 'S./' : 'USD';
@endphp

<span 
  data-target="{{ $tipo }}"
  data-currency="{{ $moneda }}"
  data-active="0"
  class="btn-currency-change  {{ $active ? 'active'  : '' }} pull-right btn-default btn btn-flat btn-xs"> 
  {{ $nombreMoneda }}
  </span>
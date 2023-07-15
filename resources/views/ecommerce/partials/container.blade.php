@php
  $import = $import ?? 1;
  $search = $search ?? 0;
@endphp

<div 
  data-url="{{ route('tienda.indexTable') }}"
  data-load="0" 
  data-search="{{ $search }}" 
  data-import="{{ $import }}" 
  id="containerOrders">
</div>

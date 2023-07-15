@php
  $route = $model->isIngreso() ? route('guia_ingreso.edit', $GuiOper) : route('guia.edit', $GuiOper); 
@endphp

<a href="{{ $route }}" > {{ $GuiOper }} </a>

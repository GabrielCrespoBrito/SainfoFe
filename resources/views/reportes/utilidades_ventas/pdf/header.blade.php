@php
  $local = $local ?? null;
  $colspan = $local ? 3 : 2;
@endphp

<div class="container header">
  <table id="header" width="100%">
    <tr> <td colspan="{{ $colspan }}" style="text-align: center; "class="titulo strong"> {{ $titulo }}</td> </tr>
    <tr>
      <td><span class="campo"> Desde:</span> {{ $fecha_desde }}</td>
      <td><span class="campo"> Hasta:</span> {{ $fecha_hasta }}</td>    
      @if($local)
      <td><span class="campo"> Local:</span> {{ $local }}</td>
      @endif
    </tr>
  </table>
</div>
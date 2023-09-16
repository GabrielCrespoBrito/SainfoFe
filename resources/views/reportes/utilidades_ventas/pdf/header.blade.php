@php
  $local = $local ?? null;
  $grupo = $grupo ?? null;
  $colspan = 2;
  
  if($local){
    $colspan++;
  }

  if($grupo){
    $colspan++;
  }

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
      @if($grupo)
      <td><span class="campo"> Grupo:</span> {{ $grupo }}</td>
      @endif
    </tr>
  </table>
</div>
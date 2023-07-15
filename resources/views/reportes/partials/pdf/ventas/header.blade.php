<div class="container header">
<table id="header" width="100%">
  <tr>
    <td colspan="4" style="text-align: center; font-weight: bold; font-size: 1.2em" class="titulo">
      @php 
        $name = $is_venta ? 'ventas' : 'guias';
      @endphp

    @switch($tipo_reporte)

      @case("venta")
        Reporte de {{ $name }}
        @break

      @case("detalle")
        Reporte de {{ $name }} detallado
        @break

      @default
        Reporte de {{ $name }} con items      
    @endswitch

  </td>
  </tr>
  <tr>
    <td>{{ $empresa['EmpNomb'] }}</td>
    <td><span class="campo">Desde:</span> {{ $fecha_desde }}</td>
    <td><span class="campo">Hasta:</span> {{ $fecha_hasta }}</td>    
    <td><span class="campo">Fecha:</span> {{ date('Y-m-d') }}</td>        
  </tr>
  <tr>
    <td><span class="campo">{{ $empresa["EmpLin1"] }}</span></td>
    <td><span class="campo">Local:</span> {{ $local }} </td>
    
     @if( $is_venta )
    <td>
      <span class="campo">Serie:</span> {{ $serie }} 
    </td>    
    @endif

    <td {{ $is_venta ? '' : "colspan=2" }} ><span class="campo">Cliente:</span> 
      @if( $cliente_select )
        {{ $cliente_info->PCNomb . ' ' . $cliente_info->PCRUcc }}
      @else
        {{ $cliente }}
      @endif

     </td>      
  </tr> 
</table>
</div>
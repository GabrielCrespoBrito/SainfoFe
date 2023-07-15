@php
$class_name = $class_name ?? "";
$total_nombre_class = $total_nombre_class ?? '';
$total_value_class = $total_value_class ?? '';
@endphp

<div class=" {{ $class_name }}">

  <table width="100%">

    @if( $gravadas !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">OP. GRAVADAS: </td>
      <td class="total_value {{ $total_value_class }}"> {{ $gravadas}} </td>
    </tr>
    @endif


    @if( $exonerada !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">OP. EXONERADAS.: </td>
      <td class="total_value {{ $total_value_class }}">{{ $exonerada }}</td>
    </tr>
    @endif

    @if( $inafecta !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">OP. INAFECTAS.: </td>
      <td class="total_value {{ $total_value_class }}"> {{ $inafecta }} </td>
    </tr>
    @endif

    @if( $totalDcto !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">TOTAL DCTO.: </td>
      <td class="total_value {{ $total_value_class }}">{{ $totalDcto }}</td>
    </tr>
    @endif

    @if( $icbper !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">ICBPER.: </td>
      <td class="total_value {{ $total_value_class }}">{{ $icbper }}</td>
    </tr>
    @endif

    @if( $isc !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">ISC.: </td>
      <td class="total_value {{ $total_value_class }}">{{ $isc }}</td>
    </tr>
    @endif

    @if( $igv !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">IGV.: </td>
      <td class="total_value {{ $total_value_class }}">{{ $igv }}</td>
    </tr>
    @endif

    @if( $percepcion_monto !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">PERCECPIÒN.: {{ $percepcion_porc }} </td>
      <td class="total_value {{ $total_value_class }}">{{ $percepcion_monto }}</td>
    </tr>
    @endif

    @if( $anticipo !== false )

    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">ANTICIPO.: </td>
      <td class="total_value {{ $total_value_class }}">{{ $anticipo }}</td>
    </tr>
    @endif

    @if( $total !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">TOTAL.: </td>
      <td class="total_value {{ $total_value_class }}">{{ $total }}</td>
    </tr>
    @endif

  </table>

</div>

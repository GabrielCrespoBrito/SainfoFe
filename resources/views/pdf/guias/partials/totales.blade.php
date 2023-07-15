@php
$class_name = $class_name ?? "";
$total_nombre_class = $total_nombre_class ?? '';
$total_value_class = $total_value_class ?? '';

// Comment loremp ipsum odlor
$gravadas = fixedValue($venta2->Vtabase,$decimals);
$exonerada = $venta2->hasMontoExonerado() ? $venta2->VtaExon : false;
$inafecta = $venta2->hasMontoInafecto() ? $venta2->VtaInaf : false;
$gratuita = $venta2->hasMontoGrauito() ? $venta2->VtaGrat : false;
$totalDcto = $venta2->hasMontoDcto() ? $venta2->VtaDcto : false;
$icbper = $venta2->hasMontoICBPER() ? $venta2->icbper : false;
$isc = $venta2->hasMontoISC() ? $venta2->VtaISC : false;
$igv = fixedValue($venta2->VtaIGVV,$decimals);
$showPercepcion = $venta2->hasMontoPercepcion();
$anticipo = $venta2->hasAnticipo() ? $venta2->VtaTotalAnticipo : false;
$total = $venta2->VtaImpo;
$style = $style ?? '';


@endphp

<div class="{{ $class_name }}" style="{{ $style }}">


  <table width="100%">

    @if( $gravadas !== false )
    <tr>
      <td class="total_nombre {{  $total_nombre_class }}">OP. GRAVADAS.: </td>
      <td class="total_value {{ $total_value_class }}"> <span class="moneda_abbr">{{ $moneda_abreviatura }} </span>  {{ fixedValue( $gravadas,$decimals )}} </td>
    </tr>
    @endif

    @if( $exonerada !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">OP. EXONERADAS.: </td>
      <td class="total_value {{ $total_value_class }}"> <span class="moneda_abbr">{{ $moneda_abreviatura }} </span> {{ fixedValue($exonerada, $decimals) }}</td>
    </tr>
    @endif

    @if( $inafecta !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">OP. INAFECTAS.: </td>
      <td class="total_value {{ $total_value_class }}">  <span class="moneda_abbr">{{ $moneda_abreviatura }} </span> {{ fixedValue($inafecta, $decimals) }} </td>
    </tr>
    @endif
    
    @if( $gratuita !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">OP. GRATUITA.: </td>
      <td class="total_value {{ $total_value_class }}">  <span class="moneda_abbr">{{ $moneda_abreviatura }} </span> {{ fixedValue($gratuita, $decimals) }} </td>
    </tr>
    @endif

    @if( $totalDcto !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">TOTAL DCTO.: </td>
      <td class="total_value {{ $total_value_class }}"> <span class="moneda_abbr">{{ $moneda_abreviatura }} </span> {{ fixedValue($totalDcto, $decimals) }}</td>
    </tr>
    @endif

    @if( $icbper !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">ICBPER.: </td>
      <td class="total_value {{ $total_value_class }}"> <span class="moneda_abbr">{{ $moneda_abreviatura }} </span> {{ fixedValue($icbper, $decimals) }}</td>
    </tr>
    @endif

    @if( $isc !== false )
   <tr>
      <td class="total_nombre {{ $total_nombre_class }}">ISC.: </td>
      <td class="total_value {{ $total_value_class }}"> <span class="moneda_abbr">{{ $moneda_abreviatura }} </span> {{ fixedValue($isc,$decimals) }}</td>
    </tr>
    @endif

    @if( $igv !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">IGV.: </td>
      <td class="total_value {{ $total_value_class }}"> <span class="moneda_abbr">{{ $moneda_abreviatura }} </span> {{ fixedValue($igv, $decimals ) }}</td>
    </tr>
    @endif

    @if( $showPercepcion )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">PERCECPIÒN.: {{ $venta2->percepcionPorc() }} % </td>
      <td class="total_value {{ $total_value_class }}"> <span class="moneda_abbr">{{ $moneda_abreviatura }} </span> {{ fixedValue( $venta2->percepcionMonto() , $decimals) }}</td>
    </tr>
    @endif
    
    @if( $anticipo !== false )

    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">ANTICIPO.: </td>
      <td class="total_value {{ $total_value_class }}"> <span class="moneda_abbr">{{ $moneda_abreviatura }} </span> {{ fixedValue($anticipo, $decimals) }}</td>
    </tr>
    @endif

    @if( $total !== false )
    <tr>
      <td class="total_nombre {{ $total_nombre_class }}">TOTAL.: </td>
      <td class="total_value {{ $total_value_class }}"> <span class="moneda_abbr">{{ $moneda_abreviatura }} </span> {{ fixedValue( $total, $decimals ) }}</td>
    </tr>
    @endif

  </table>

</div>
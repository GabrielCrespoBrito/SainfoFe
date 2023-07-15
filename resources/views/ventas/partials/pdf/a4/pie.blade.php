@php
$showExonerada = $venta2->hasMontoExonerado();
$showInafecta = $venta2->hasMontoInafecto();
$showGratuita = $venta2->hasMontoGrauito();
$showDescuento = $venta2->hasMontoDcto();
$showISC = $venta2->hasMontoISC();
$showPercepcion = $venta2->hasMontoPercepcion();
$showRetencion = $venta2->hasMontoRetencion();
$showDetraccion = $venta2->hasDetraccion();
$showICBPER = $venta2->hasMontoICBPER();
$showAnticipo = $venta2->hasAnticipo();
$is_boleta = $venta2->isBoleta();
$hasPlaca = $venta2->hasPlaca();
$retencion_monto = 0;
@endphp

<div class="row_pie">


  @if($venta2->isNotaCredito() || $venta2->isNotaDebito() )
  <div class="referencia">
    <table width="100%">
      <tr style="text-align: center;">
        <td width="20%"><span class="nombre_ref">Referencia:</span> <span class="value_ref">{{ $venta2->VtaTDR }}</span></td>
        <td width="10%"> {{ $venta2->VtaSeriR }} </td>
        <td width="10%"> {{ $venta2->VtaNumeR }} </td>
        <td width="10%"> {{ $venta2->VtaFVtaR }} </td>

        <td width="50%">
          {{-- <span class="nombre_ref">Motivo:</span>  --}}
          <span class="value_ref"> <strong>Motivo: </strong> {{ $venta2->VtaObse }}</span>
        </td>
      </tr>
    </table>
  </div>
  @endif


  @if($showDetraccion)
  @php
    $porcentaje_detr = fixedValue($venta2->VtaDetrPorc);
    $descripcion_detraccion = $venta2->detraccion->descripcion;
    $code_detraccion = $venta2->VtaDetrCode;
    $porcentaje_total = fixedValue($venta2->VtaDetrTota);
  @endphp
  <div class="referencia">
    <table width="100%">
      <tr style="text-align: center;">
      <td width="60%"><span class="nombre_ref">Detracciòn:</span> <span class="value_ref"> {{ $descripcion_detraccion }} <strong> ({{ $code_detraccion }}) </strong> </span> </td>
      <td width="20%"><span class="nombre_ref">Porcentaje:</span> <span class="value_ref"> {{ $porcentaje_detr }}%</span></td>
      <td width="20%"><span class="nombre_ref">Total:</span> <span class="value_ref"> {{ $porcentaje_total }}</span></td>
      </tr>
    </table>
  </div>
  @endif


  {{-- <div class="page-break"></div> --}}

  <table class="pie">
  @if($showRetencion)
    @php
      $retencion_porc = $venta2->retencionPorc();
      $retencion_monto = $venta2->retencionMonto();
    @endphp

    <tr>
      <td colspan="2" style="border-bottom: 1px solid #000">
        <div class="total_le">
          <span> Información de la retención:  </span>
          <span style="font-weight:bold"> Base imponible </span>: {{ $moneda_abreviatura }} {{ fixedValue($venta2->VtaImpo,$decimals) }}  
          <span style="font-weight:bold"> Porcentaje: </span> {{ $retencion_porc }}% 
          <span style="font-weight:bold"> Monto: </span> {{ $moneda_abreviatura }} {{ $retencion_monto }}
        </div>
      </td>
    </tr>
  @endif

    <tr>
      <td colspan="2" style="border-bottom: 1px solid #000">
        <div class="total_le">
          SON: <span id="numero_palabra"> {{ $cifra_letra }} </span>
        </div>
      </td>
    </tr>

    @if($hasPlaca)
    <tr>
      <td colspan="2" style="border-bottom: 1px solid #000">
        <div class="total_le">
          PLACA: <span id="numero_palabra"> {{ $venta2->getPlaca() }} </span>
        </div>
      </td>
    </tr>
    @endif



    @if($showAnticipo)
    <tr>
      <td colspan="2" style="border-bottom: 1px solid #000">
        <div class="total_le">
          ANTICIPO:
          <strong>Doc:</strong> <span>{{ $venta2->getNombreTipoDocucumentoAnticipo() }} {{ $venta2->VtaNumeAnticipo }} </span>
          <span class="importe-anticipo">
            <strong>Total:</strong> <span> {{ $moneda_abreviatura }} {{ decimal($venta2->VtaTotalAnticipo) }} </span>
            <span>
        </div>
      </td>
    </tr>
    @endif

    <tr>
      <td width="50%" class="data_1" valign="top">
        <table class="">
          <tr>
            <td class="td-qr" style="width:30%">
              <img src="data:image/png;base64, {!! base64_encode($qr)!!} ">
            </td>
            <td valign="bottom">
              <p> <strong style="text-decoration: underline"> Resumen:</strong> {{ $firma }} </p>
              <p> <strong> Hora:</strong> {{ $venta2->VtaHora }} </p>
              <p> <strong> Peso:</strong> {{ decimal($venta2->getPesoTotal()) }} <strong> Kgs. </strong> </p>
              @if( ! $is_boleta )
              <p>Representaciòn Impresa de {{ $venta['nombre_documento']  }} </p>
              <p class="descripcion">Esta puede ser consultada en: {{ $empresa['fe_consulta'] }} </p>
              @endif
            </td>
          </tr>
        </table>
      </td>

      <!-- Totales -->
      <td width="50%" class="data_2" valign="top">
        <table class="table_totales" width="100%" height="100%">
          <tr style="padding:10px">
            <td class="nombre_total"> T.v.v. - Operciones Gravadas <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ fixedValue($venta2->Vtabase,$decimals) }} </td>
          </tr>

          @if( $showInafecta )
          <tr>
            <td class="nombre_total"> T.v.v. - Operciones Inafectas <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ fixedValue($venta2->VtaInaf,$decimals) }} </td>
          </tr>
          @endif

          @if( $showExonerada )
          <tr>
            <td class="nombre_total"> T.v.v. - Operciones Exoneradas <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ fixedValue($venta2->VtaExon,$decimals) }} </td>
          </tr>
          @endif

          @if( $showGratuita )
          <tr>
            <td class="nombre_total"> T.v.v. - Operciones Gratuitas <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ fixedValue($venta2->VtaGrat,$decimals) }} </td>
          </tr>
          @endif

          @if( $showICBPER )
          <tr>
            <td class="nombre_total"> ICBPER <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ decimal($venta2->icbper) }} </td>
          </tr>
          @endif

          @if( $showISC )
          <tr>
            <td class="nombre_total"> ISC <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ decimal($venta2->VtaISC) }} </td>
          </tr>
          @endif

          <tr>
            <td class="nombre_total"> IGV : {{ $empresa['igv_porc'] }}% <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ fixedValue($venta2->VtaIGVV,$decimals) }} </td>
          </tr>

          @if( $showDescuento )
          <tr>
            <td class="nombre_total"> Total Descuento <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ fixedValue($venta2->VtaDcto,$decimals) }} </td>
          </tr>
          @endif

          @if( $showPercepcion )
          @php
            $percepcion_porc =   $venta2->percepcionPorc();
            $percepcion_monto = $venta2->percepcionMonto();
          @endphp
          <tr>
            <td class="nombre_total"> Percepciòn  {{ $percepcion_porc }}% <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ fixedValue($percepcion_monto,$decimals) }} </td>
          </tr>
          @endif

          @if( $showAnticipo )
          <tr>
            <td class="nombre_total"> Anticipo <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ decimal($venta2->VtaTotalAnticipo) }} </td>
          </tr>
          @endif

          <tr>
            <td class="nombre_total"> Importe total de la Venta <span class="mon">{{ $moneda_abreviatura }}</span> </td>
            <td class="total_cifra" valign="top"> {{ fixedValue($venta2->VtaImpo,$decimals) }} </td>
          </tr>
        </table>
      </td>
      <!-- /Totales -->
    </tr>

    <!-- Condicion de venta y cuentas -->
    <tr class="condicion_tr">
      @php
        $isCredito = $venta2->isCredito();
        $total = $venta2->VtaImpo - $retencion_monto;
        
        if( $isCredito ){
          $creditos = $venta2->getCreditos();
          $fp = $venta2->forma_pago;
          $total = $creditos->sum('monto');
        }

      @endphp
      <td class="td_ele" width="50%" valign="top">
        <div class="title_c" style="border-bottom:1px solid black"> Monto neto Pendiente de Pago: <span class="total-pendiente-pago"> {{ $moneda_abreviatura }} {{ fixedValue($total,$decimals) }} </span> </div>

      <!--  -->
      @if( $isCredito )
        <table width="100%">
          <thead>
            <tr style="text-align: left;">
              <td width="20%" style="text-align:center"> <strong> N° Cuota </strong> </td>
              <td width="40%" style="text-align:center"> <strong> Fec. Venc. </strong> </td>
              <td width="40%" class="text-align:left"> <strong> Monto </strong> </td>
            </tr>
          </thead>
          <tbody>

          @foreach( $creditos as $credito )
          <tr style="text-align: left;">
            <td style="text-align:center"> {{ (int) $credito->item }} </td>
            <td style="text-align:center"> {{ $credito->fecha_pago }} </td>
            <td style="text-align: left;"> {{ $credito->getMonedaAbreviatura() }} {{ $credito->monto }} </td>
          </tr>
          @endforeach
          </tbody>
        </table>

        @else
        @foreach( $condiciones as $condicion )
        @if( $condicion != "" )
        <div> - {{ trim($condicion) }}</div>
        @endif
        @endforeach

        @endif
      </td>

      <td class="td_ele border-top" width="50%" valign="top" style="border-left:1px solid #666">
        <div class="title_c" style="border-bottom:1px solid black"> CUENTAS </div>
        @foreach( $bancos as $banGroup => $cuentas )
        @foreach( $cuentas as $cuenta )
        <div>
          {{ $cuenta->banco->bannomb }}
          {{ $cuenta->moneda->monabre }}
          <strong>{{ $cuenta->CueNume }}</strong>
        </div>
        @endforeach
        @endforeach
      </td>

    </tr>
    <!-- Condicion de venta y cuentas -->

  </table>
</div>
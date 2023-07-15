@extends('layouts.pdf.master')
@section('title_pagina', $nombre_reporte)

@section('content')


  @component('components.reportes.reporte_basico.pdf', [
    'nombre_reporte' => $nombre_reporte,
    'ruc' => $ruc,
    'nombre_empresa' => $nombre_empresa,
    ])



  {{-- Filtros --}}
  @slot('filtros')

    <table class="table-header-informacion" width="100%">
      <tr>
        <td with="15%"> 
          <span class="bold">Fecha desde: </span> <span> {{ $fecha_desde }}</span> 
        </td>

        <td with="15%">
          <span class="bold">Fecha hasta: </span> <span> {{ $fecha_hasta }}</span>
        </td>

        <td with="40%">
          <span class="bold">Proveedor: </span> <span> {{ $proveedor }}</span>
        </td>

        <td with="30%">
          <span class="bold tipocambio">Tipo: </span> <span> {{ $tipodocumento }}</span>
        </td>      

      </tr>    
    </table> 
    @endslot
    {{-- /Filtros --}}

  @slot('content')

    @php
      $subTotal = 0;
      $igv = 0;
      $total = 0;
    @endphp

    <table class="table-contenido" width="100%">
      <thead>
        @if( $withProducts )
        <tr> 
          <td> Fecha  </td>
          <td> TD  </td>
          <td> Proveedor  </td>
          <td> Unidad  </td>
          <td> Cantidad </td>
          <td> Mon </td>
          <td> Precio  </td>
        </tr> 
        @else
        <tr> 
          <td> Fecha  </td>
          <td> N° Oper.  </td>
          <td> N° Doc.  </td>
          <td> RUC/DNI  </td>
          <td> Razón Social </td>
          <td> Mon </td>
          <td> Valor  </td>
          <td> IGV </td>
          <td> Total </td>
          <td> Perc </td>
          <td> Total S/ </td>
          <td> T.C  </td>
        </tr> 
        @endif
      </thead>

      <tbody>
        @if($withProducts)

        @else

          @php
            $nombresDocuentos = ['01' => 'FACTURA' , '03' => 'BOLETA', '07' => 'NOTA CREDITO', '08' => 'NOTA DEBITO', '40' => 'COMPRA LIBRE' ];
          @endphp

        @foreach( $items_group as $key => $items )

          {{-- Sub total del documento --}}
          @php
            $subTotalGroup = 0.00;
            $igvGroup = 0.00;
            $totalGroup = 0.00;
          @endphp

          <tr>
            <td> <strong> {{ $key }}  </strong></td>
            <td> <strong> {{ $nombresDocuentos[$key] }} </strong></td>
          </tr>            

        @foreach( $items as $item )

          @php            
            $tCambio = $item->MonCodi == "01" ? '-' : $item->CpaTcam;
            $subTotalGroup += $item->MonCodi == "01" ? $item->Cpabase : $item->Cpabase * $item->CpaTcam;
            $igvGroup += $item->MonCodi == "01" ? $item->CpaIGVV : $item->CpaIGVV * $item->CpaTcam;
            $totalGroup += $totalSoles = $item->MonCodi == "01" ? $item->Cpatota : $item->Cpatota * $item->CpaTcam;
          @endphp
          <tr>
            <td>{{ $item->CpaFCpa }}</td>
            <td>{{ $item->CpaOper }}</td>
            <td>{{ $item->CpaNume }}</td>
            <td>{{ $item->PCRucc }}</td>
            <td>{{ $item->PCNomb }}</td>
            <td>{{ $item->monnomb }}</td>
            <td style="text-align: right;">{{ math()->addDecimal($item->Cpabase) }}</td>
            <td style="text-align: right;">{{ math()->addDecimal($item->CpaIGVV) }}</td>
            <td style="text-align: right;">{{ math()->addDecimal($item->Cpatota) }}</td>
            <td style="text-align: right;"> 0.00 </td> {{-- Percepción --}}
            <td style="text-align: right;">{{ $totalSoles }} </td>
            <td style="text-align: right;">{{  $tCambio }}</td>
          </tr>
          @endforeach

          @php
            $subTotal += $subTotalGroup;
            $igv += $igvGroup;
            $total += $totalGroup;
          @endphp

          {{-- Subtotal --}}
         <tr class="totales">
            <td style="border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> SubTotal Valor Cpa S./ </td> 
            <td style="text-align: right;border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> <strong> {{ math()->addDecimal($subTotalGroup) }} </strong> </td> 
            <td style="text-align: center; border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> SubTotal IGV S./:  </td> 
            <td style="text-align: right; border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> <strong> {{ math()->addDecimal($igvGroup) }} </strong> </td> 
            <td style="text-align: center; border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> SubTotal S./: </td> 
            <td style="text-align: right; text-align: right;  border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> <strong> {{ math()->addDecimal($totalGroup) }} </strong> </td> 
            <td colspan="6" style="text-align: right; border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";>  </td>
          </tr>
          
          @endforeach
        @endif
      </tbody>
      <tfoot>
         <tr class="totales" style=" border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> 
            <td style=" border-top: 1px dotted #333333; border-bottom: 1px dotted #333333;"> Total Valor Cpa S./:</td>
            <td style="text-align: right;  border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> <strong> {{ math()->addDecimal($subTotal) }} </strong></td>
            <td style=" text-align: center; border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> Total IGV S./:  </td>
            <td style=" text-align: right; border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> <strong> {{ math()->addDecimal($igv) }} </strong> </td>
            <td style="text-align: center;  border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> Total S./:  </td>
            <td style="text-align: right; border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";> <strong> {{ math()->addDecimal($total) }} </strong> </td>
            <td colspan="6" style="text-align: right; border-top: 1px dotted #333333; border-bottom: 1px dotted #333333";";>  </td>
          </tr>
      </tfoot>

      </table>
    @endslot

  @endcomponent
@endsection

@php

  // @TODO Refactorizar, ver tbodycreate en esta misma carpeta
@endphp

@if( !$create )


@foreach( $cotizacion->items as $item ) 

    @if( $modify )

      @php
        $producto = $item->producto;
        $unidades = $producto->unidades->load('lista');
        $unidad = $item->unidad;
        $precio = $item->DetPrec;
        $total = $item->DetImpo;
        $descuento = $item->DetDcto;
      @endphp

      @include('cotizaciones.partials.factura.table.tr',[
      'item' => $item->DetItem,
      'tiecodi' => $producto->tiecodi,
      'base' => $item->DetBase,
      'unidad_code' => $item->UniCodi,
      'detcodi' => $item->DetCodi,
      'unidad_nombre' => $unidad->withListaName(),
      'nombre'    => $item->DetNomb,
      'marca'     => $producto->MarNomb,
      'cantidad' => $item->DetCant,
      'precio'    => $item->DetPrec,
      'descuento' => $item->DetDcto,
      'importe' => $item->DetImpo,
      'accion'    => true,
      'info'      => $item,
      'unidades' => $unidades,
      ])


    @else


      <?php 
        $item = $item->getAttributes();
        $item_array = $item;
        $item_array['ProCodi'] = $item["DetCodi"];
        $item_array['DetUni'] = $item_array['DetUnid'];
        $item_array['DetUniNomb'] = $item_array['DetUnid'];
        $item_array['DetPvta'] = $item_array['DetPrec'];
        $item_array['UniCodi'] = $item_array['DetCodi'];
      ?>


      <tr class="tr_item">
        <td class="DetItem" data-campo="DetItem"> {{ $item["DetItem"] }} </td>        
        <td class="TieCodi" data-campo="TieCodi">{{ $item["DetItem"] }} </td>
        <td class="DetBase" data-campo="DetBase">{{ $item["DetBase"] }} </td>
        <td class="UniCodi" data-campo="UniCodi">{{ $item["DetCodi"] }} </td>    
        <td class="DetUnid" data-campo="DetUnid">{{ $item["DetUnid"] }} </td>    
        <td class="DetNomb" data-campo="DetNomb">{{ $item["DetNomb"] }}
          @if( $item["DetDeta"] )
          <br>
          <span style="font-style: italic;"> {{ $item["DetDeta"] }} </span>
          @endif
          </td> 

        <td class="DetCant text-right" data-campo="DetCant">{{ $item['MarNomb'] }} </td>
        <td class="DetCant text-right" data-campo="DetCant">{{ $item["DetCant"] }} </td>
        <td class="DetPrec text-right" data-campo="DetPrec">{{ fixedValue($item["DetPrec"]) }} </td>
        <td class="DetDcto text-right" data-campo="DetDcto">{{ fixedValue($item["DetDcto"]) }} </td>
        <td class="DetImpo text-right" data-campo="DetImpo">{{ fixedValue($item["DetImpo"]) }} </td>
        </td>
      </tr>

    @endif

    @endforeach 
    
@endif
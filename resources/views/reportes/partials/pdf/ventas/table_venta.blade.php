<div class="container div_items">
  <table class="table_items" id="table_venta" width="100%">      
    <tr class="thead" style="font-weight: bold">
      <td>Fecha</td>
      <td>N° Oper</td>
      @if( !$cliente_select )
      <td style="text-align:center">RUC/DNI</td>
      <td>Razón Social</td>
      @endif
      <td>Vendedor</td>
      <td>Estado</td>        
      <td>Moneda</td>        
      <td style="text-align: right">Valor.Vta</td>
      @if($is_venta)             
      <td style="text-align: right">IGV</td>        
      <td style="text-align: right">Total</td>                
      <td style="text-align: right">Total S.</td>        
      <td style="text-align: right">TC.</td>
      @else 
        <td>Estado Sunat</td>
      @endif               
    </tr>      

    <!-- items -->
    @php
    $total_items = 0;
    $total_base = 0;
    $total_igv = 0;
    $total_total = 0;
    $total_perc = 0;    
    @endphp

    @foreach( $ventas_groups as $id => $ventas_group )

      @if( $is_venta )
      <tr class="documento_tr" style="padding: 10px 0;">
        <td colspan="12">     

          {{ $ventas_group->first()->tipo_documento->TidNomb }}
          ({{ $ventas_group->first()->tipo_documento->TidCodi }})
        </td>  
      </tr>
      @endif

      @foreach( $ventas_group as $venta )
        @if($is_venta)
        @php
          $cliente = optional($venta->cliente);
        @endphp
        <tr class="body_tr">  
          <td width="80px">{{ $venta->VtaFvta }}</td>
          <td width="40">{{ $venta->numero() }}</td>
          @if( !$cliente_select )
          <td style="text-align:center">{{ $cliente->PCRucc }}</td>
          <td >{{ substr($cliente->PCNomb, 0,20) }}</td>
          @endif  
          <td>{{ $venta->vendedor->vennomb }}</td>
          <td >{{ $venta->VtaEsta }}</td>        
          <td >{{ $venta->moneda->monabre }}</td>              
          <td  style="text-align: right">{{ $venta->Vtabase }}</td>                
          <td  style="text-align: right">{{ $venta->VtaIGVV }}</td>        
          <td  style="text-align: right">{{ $venta->VtaTota }}</td>                
          <td  style="text-align: right">{{ $venta->VtaImpo }}</td>        
          <td  style="text-align: right"> {{ $venta->VtaTcam }} </td>        
        </tr>

        @else
          @php $guia = $venta; @endphp
          @php
            $cliente = optional($guia->cliente);
          @endphp
         <tr class="body_tr">  
            <td width="80px">{{$guia->GuiFemi  }}</td>
            <td width="40">{{ $guia->GuiSeri . '-' . $guia->GuiNumee  }}</td>
            @if( !$cliente_select )
            <td >{{ $cliente->PCRucc }}</td>
            <td >{{ substr($cliente->PCNomb, 0,20) }}</td>
            @endif  
            <td>{{ $guia->vencodi  }}</td>
            <td>{{ $guia->GuiEOpe  }}</td>        
            <td>{{ $guia->moneda->monabre  }}</td>              
            <td>{{ $guia->guitbas }}</td> 
            <td>{{ $guia->nombreEstado() }} </td>                    
          </tr>        
        @endif
      @endforeach

      {{-- foreach ventas-guias --}}

      @php
      
        $isNC = $id == "07";

        if( $is_venta ){
          logger([ time(), $loop->index, $id,  $ventas_group->sum('Vtabase'), $ventas_group->sum('IGVV'), $ventas_group->sum('VtaImpo') ]);

          $total_items += $items = $ventas_group->count(); 
          $total_base += $base = convertNegativeIfTrue($ventas_group->sum('Vtabase'), $isNC  );
          $total_igv += $igv =  convertNegativeIfTrue($ventas_group->sum('VtaIGVV'), $isNC  );
          $total_total += $total =  convertNegativeIfTrue($ventas_group->sum('VtaImpo'), $isNC  ); 
          $total_perc += $perc = convertNegativeIfTrue($ventas_group->sum('VtaPerc'), $isNC  );
        }

        else {
          $total_items += $items = $ventas_group->count(); 
          $total_total += $base = $ventas_group->sum('guitbas'); 
        }
      @endphp

      @if($is_venta)

     <tr class="totales_tr">
        <td colspan="3"><span class="campo">Items:</span> <span class="value_total"> {{ $items }} </span> </td>  
        <td colspan="3"><span class="campo">Sub Total Valor Vta S/:</span> <span class="value_total">{{ $base }}</span> </td>  
        <td colspan="3"><span class="campo">Sub Total IGV  S/:</span> <span class="value_total">{{ $igv }}</span> </td>  
        <td colspan="3"><span class="campo">Sub Total S/:</span> <span class="value_total">{{ $total }}</span> </td>  
      </tr>

      @else
        @php
          $colspan_size = $cliente_select ? 5 : 5;
        @endphp

       <tr class="totales_tr">
          <td colspan="{{ $colspan_size }}"><span class="campo">Items:</span> <span class="value_total"> {{ $items }} </span> </td>  
          <td colspan="{{ $colspan_size }}"><span class="campo">Sub Total Valor Vta S/:</span><span class="value_total">{{$base}}</span></td>
        </tr> 
      @endif


    @endforeach

    @if($is_venta)

      <tr class="totales_tr total_table">
        <td colspan="3"></td>  
        <td colspan="3"><span class="campo">Total Valor Vta S/:</span> <span class="value_total">{{ $total_base }}</span> </td>  
        <td colspan="3"><span class="campo">Total IGV  S/:</span> <span class="value_total">{{ $total_igv }}</span> </td>  
        <td colspan="3"><span class="campo">Total S/:</span> <span class="value_total">{{ $total_total }}</span> </td>  
      </tr>

    @endif

    {{-- foreach grupo --}}







{{-- 


    <tr class="totales_tr total_table">
      <td colspan="3"></td>  
      <td colspan="3"><span class="campo">Total Valor Vta S/:</span> <span class="value_total">{{ $total_base }}</span> </td>  
      <td colspan="3"><span class="campo">Total IGV  S/:</span> <span class="value_total">{{ $total_igv }}</span> </td>  
      <td colspan="3"><span class="campo">Total S/:</span> <span class="value_total">{{ $total_total }}</span> </td>  
    </tr>
 --}}

  </table>
</div>
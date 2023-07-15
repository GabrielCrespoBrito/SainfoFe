<div class="container div_items">
  <table class="table_items" id="table_venta" width="100%">      
    <tr class="thead">
      <td>Fecha</td>
      <td>N° Oper</td>
      @if( !$cliente_select )
      <td>RUC/DNI</td>      
      <td>Razón Social</td>
      @endif
      <td>Vendedor</td>
      <td>Item</td>        
      <td>UNID</td>        
      <td>Articulo</td>              
      <td>Cant.</td>                
      <td>Prec.</td>        
      <td>Impo.</td> 
      @if($is_venta)               
        <td>Venta.</td>
        <td>Pag.</td>      
        <td>Credito.</td>            
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
    
    @if($is_venta)

    <tr class="documento_tr" style="padding: 10px 0;">
      <td colspan="14">
        <strong> ---
        {{ $ventas_group->first()->tipo_documento->TidNomb }} ---
        </strong>
      </td>  
    </tr>
    @endif

      @foreach( $ventas_group as $venta )
      @php $guia = $venta; @endphp      
        @foreach( $venta->items as $item )
          @if($is_venta)
            <tr class="body_tr">  
              <td  width="80px">{{ $venta->VtaFvta }}</td>
              <td> {{ $venta->VtaNume }} </td>
              @if(!$cliente_select)
              <td> {{ optional($venta->cliente)->PCRucc }}</td>
              <td> {{ substr(optional($venta->cliente)->PCNomb, 0,20) }}</td>
              @endif
              <td> {{ $venta->Vencodi }}</td>
              <td> {{ $item->DetItem }}</td>                    
              <td> {{ $item->DetUnid }}</td>        
              <td> {{ $item->DetNomb  }}</td>              
              <td style="text-align: right"> {{ $item->DetCant }}</td>                
              <td style="text-align: right"> {{ $item->DetPrec }}</td>        
              <td style="text-align: right"> {{ $item->DetImpo }}</td>                
              <td style="text-align: right"> {{ $venta->VtaPago }}</td>
              <td style="text-align: right"> {{ $venta->VtaTota }}</td>        
              <td style="text-align: right"> 0</td>        
            </tr>
          @else

            <tr class="body_tr">  
              <td width="80px">{{$guia->GuiFemi  }}</td>
              <td width="40">{{ $guia->GuiSeri . '-' . $guia->GuiNumee  }}</td>
              @if( !$cliente_select )
              <td >{{ $guia->cliente->PCRucc }}</td>
              <td >{{ substr($guia->cliente->PCNomb, 0,20) }}</td>
              @endif 
              <td>{{ $guia->vencodi  }}</td>
              <td>{{ $item->DetItem  }} </td>              
              <td>{{ $item->DetUnid  }} </td>        
              <td>{{ $item->DetNomb  }}</td>                      
              <td style="text-align: right">{{ $item->DetCant  }}</td>                
              <td style="text-align: right">{{ $item->DetPrec  }}</td>        
              <td style="text-align: right">{{ $item->DetImpo  }}</td>                           
            </tr>

          @endif        
        @endforeach  
      @endforeach

    @php


      if( $is_venta ){
        $total_items += $items = $ventas_group->count(); 
        $total_base += $base = $ventas_group->sum('Vtabase'); 
        $total_igv += $igv = $ventas_group->sum('VtaIGVV');
        $total_total += $total = $ventas_group->sum('VtaTota'); 
        $total_perc += $perc = $ventas_group->sum('VtaPerc');          

      }

      else {
      $total_items += $items = $ventas_group->count(); 
      $total_total += $base = $ventas_group->sum('guitbas'); 
      }


    @endphp

    @if($is_venta)
    <tr class="totales_tr">
      <td colspan="2"><span class="campo">Items:</span> <span class="value_total"> {{ $items }} </span> </td>  
      <td colspan="3"><span class="campo">Sub Total Valor Vta S/:</span> <span class="value_total">{{ $base }}</span> </td>  
      <td colspan="3"><span class="campo">Sub Total IGV  S/:</span> <span class="value_total">{{ $igv }}</span> </td>  
      <td colspan="3"><span class="campo">Sub Total S/:</span> <span class="value_total">{{ $total }}</span> </td>  
      <td colspan="3"><span class="campo">Sub Total Perc Vta S/:</span> <span class="value_total">{{ $perc }} </span></td>  
    </tr>
    @else

      @php

        // if( $cliente_select ){
        //   $colsize1 = 4
        //   $colsize2 = 5;          
        // }
        // else {
        //   $colsize1 = 5
        //   $colsize2 = 6;          
        // }
      @endphp

       <tr class="totales_tr">

          <td colspan="4"><span class="campo">Items:</span> <span class="value_total">{{ $items }} </span> </td>  

          <td colspan="5"><span class="campo">Sub Total Valor Vta S/:</span> <span class="value_total">{{ $base }}</span> </td>  

        </tr> 

    @endif


    @endforeach
    <!-- /items -->

    @if($is_venta)
    <tr class="totales_tr total_table">
      <td colspan="2"></td>  
      <td colspan="3"><span class="campo">Total Valor Vta S/:</span> <span class="value_total">{{ $total_base }}</span> </td>  
      <td colspan="3"><span class="campo">Total IGV  S/:</span> <span class="value_total">{{ $total_igv }}</span> </td>  
      <td colspan="6"><span class="campo">Total S/:</span> <span class="value_total">{{ $total_total }}</span> </td>  
    </tr>
    @endif


  </table>
</div>
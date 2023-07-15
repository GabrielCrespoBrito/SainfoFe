<div class="container div_items">
  <table class="table_items" id="table_venta" width="100%">      
    <tr class="thead">
      <td>Fecha</td>
      <td>N° Oper</td>
      <td>RUC/DNI</td>
      <td>Razón Social</td>
      <td>Vendedor</td>
      <td>Item</td>        
      <td>UNID</td>        
      <td>Articulo</td>              
      <td>Cant.</td>                
      <td>Prec.</td>        
      <td>Impo.</td>                
      <td>Venta.</td>
      <td>Pag.</td>      
      <td>Credito.</td>            
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
    
    <tr class="documento_tr" style="padding: 10px 0;">
      <td colspan="14">{{ $ventas_group->first()->tipo_documento->TidNomb }}
        <span class="tipo_documento_id"> {{ $id }} </span> 
      </td>  
    </tr>

      @foreach( $ventas_group as $venta )

        @foreach( $venta->items as $item )
          <tr class="body_tr">  
            <td  width="80px">{{ $venta->VtaFvta }}</td>
            <td> {{ $venta->VtaOper }} </td>
            <td> {{ $venta->cliente->PCRucc }}</td>
            <td> {{ $venta->cliente->PCNomb }}</td>
            <td> {{ $venta->Vencodi }}</td>
            <td> {{ $item->DetItem }}</td>                    
            <td> {{ $item->DetUnid }}</td>        
            <td> {{ $item->DetNomb  }}</td>              
            <td> {{ $item->DetCant }}</td>                
            <td> {{ $item->DetPrec }}</td>        
            <td> {{ $item->DetImpo }}</td>                
            <td> {{ $venta->VtaPago }}</td>
            <td> {{ $venta->VtaTota }}</td>        
            <td> 0</td>        
          </tr>
        @endforeach    
      @endforeach

    @php

    $total_items += $items = $ventas_group->count(); 
    $total_base += $base = $ventas_group->sum('Vtabase'); 
    $total_igv += $igv = $ventas_group->sum('VtaIGVV');
    $total_total += $total = $ventas_group->sum('VtaTota'); 
    $total_perc += $perc = $ventas_group->sum('VtaPerc');   

    @endphp

    <tr class="totales_tr">
      <td colspan="2"><span class="campo">Items:</span> <span class="value_total"> {{ $items }} </span> </td>  
      <td colspan="3"><span class="campo">Sub Total Valor Vta S/:</span> <span class="value_total">{{ $base }}</span> </td>  
      <td colspan="3"><span class="campo">Sub Total IGV  S/:</span> <span class="value_total">{{ $igv }}</span> </td>  
      <td colspan="3"><span class="campo">Sub Total S/:</span> <span class="value_total">{{ $total }}</span> </td>  
      <td colspan="3"><span class="campo">Sub Total Perc Vta S/:</span> <span class="value_total">{{ $perc }} </span></td>  
    </tr>

    @endforeach
    <!-- /items -->

    <tr class="totales_tr total_table">
      <td colspan="2"></td>  
      <td colspan="3"><span class="campo">Total Valor Vta S/:</span> <span class="value_total">{{ $total_base }}</span> </td>  
      <td colspan="3"><span class="campo">Total IGV  S/:</span> <span class="value_total">{{ $total_igv }}</span> </td>  
      <td colspan="3"><span class="campo">Total S/:</span> <span class="value_total">{{ $total_total }}</span> </td>  
      <td colspan="3"><span class="campo">Total Perc Vta S/:</span> <span class="value_total">{{ $total_perc }}</span> </td>  
    </tr>


  </table>
</div>
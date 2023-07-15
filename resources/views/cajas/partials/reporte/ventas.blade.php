<div class="container div_items" style="width:100%">
  
  <table class="table_items" id="table_venta" width="100%">       
    
    
    <tr class="tr_item-head">
      <td class="s"> Ventas </td>
      <td class="s"> S/. </td>
      <td class="s"> US$ </td>
      <td class="s"> Compras </td>
      <td class="s"> S/. </td>
      <td class="s"> US$ </td>
    </tr>

    <tr>
      <td> FACTURA </td>
      <td> {{ $ventas_data['01'][0] }} </td>
      <td> {{ $ventas_data['01'][1] }} </td>
      <td> FACTURA </td>
      <td> 0.00 </td>
      <td> 0.00 </td>            
    </tr>

    <tr>
      <td> BOLETA </td>
      <td> {{ $ventas_data['03'][0] }} </td>
      <td> {{ $ventas_data['03'][1] }} </td>
      <td> BOLETA </td>
      <td> 0.00 </td>
      <td> 0.00 </td>            
    </tr>
    
    <tr>
      <td> NOTA CRED </td>
      <td> {{ $ventas_data['07'][0] }} </td>
      <td> {{ $ventas_data['07'][1] }} </td>
      <td> NOTA CRED </td>
      <td> 0.00 </td>
      <td> 0.00 </td>            
    </tr>

    <tr>
      <td> NOTA DEB </td>
      <td> {{ $ventas_data['08'][0] }} </td>
      <td> {{ $ventas_data['08'][1] }} </td>
      <td> NOTA DEB </td>
      <td> 0.00 </td>
      <td> 0.00 </td>            
    </tr>

    <tr class="tr-bottom">
      <td class="s"> Total </td>
      <td class="s"> {{ $ventas_data['total'][0] }} </td>
      <td class="s"> {{ $ventas_data['total'][1] }} </td>
      <td class="s">  </td>
      <td class="s"> 0.00 </td>
      <td class="s"> 0.00 </td>            
    </tr>

  </table>
</div>
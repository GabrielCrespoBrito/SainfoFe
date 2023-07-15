  @php      
    $subTotal = $subTotal ?? false;
  @endphp
  <tr class="totales"> 
    <td colspan="5"> {{ $subTotal ? 'Sub' : '' }} Total S./ :  <strong> {{ deci($sol) }} </strong></strong>  </td>
    <td colspan="6"> {{ $subTotal ? 'Sub' : '' }} Total USD$: <strong> {{ deci($dolar)  }} </strong></td>          
  </tr>  
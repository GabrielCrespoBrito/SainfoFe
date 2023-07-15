<table>
  <tr style=" border-bottom: 1px solid black"> 
    <td width="100px"> Tipo: </td>
    <td> <span style="font-weight: bold; border-bottom: 1px solid black"> {{ $data['tipo_documento']  }} </span> </td>
  </tr> 

  <tr style=" border-bottom: 1px solid black"> 
    <td width="100px"> Numero: </td>
    <td> <span style="font-weight: bold; border-bottom: 1px solid black"> {{ $data["cliente_documento"] }}</span> </td>
  </tr>

  <tr style=" border-bottom: 1px solid black"> 
    <td width="100px"> Monto: </td>
    <td> <span style="font-weight: bold; border-bottom: 1px solid black"> {{ $data['moneda'] }} {{ $data['monto'] }} </span> </td>
  </tr> 

  <tr style=" border-bottom: 1px solid black"> 
    <td width="100px"> Fecha emisión: </td>
    <td> <span style="font-weight: bold; border-bottom: 1px solid black"> {{ $data['fecha'] }} </span> </td>
  </tr> 
</table>
@php
  $title = $title ?? 'RESUMEN DE CAJA CHICA';

@endphp
<div class="container header">
<table id="header" width="100%">
  <tr> <td colspan="4" class="titulo"> {{ $title }} </td> </tr>
  <tr> 
    <td colspan="2">  {{ $empresa['EmpNomb'] }} </td> 
    <td> <span class="campo">Usuario:</span> {{  $caja->User_Crea }}</td>    
    <td> <span class="campo">Fecha Impresión:</span> {{ date('Y-m-d H:m:i') }} </td>    
  </tr>
  <tr>
    <td> <span class="campo">Caja: </span> {{ $caja->CajNume  }}</td>
    <td> <span class="campo">Fecha apertura:</span> {{  $caja->CajFech }}</td>
    <td> <span class="campo">Fecha cierre:</span> {{  $caja->CajFecC }}</td>    
    <td> <span class="campo">Estado:</span> {{  $caja->estado }}</td>    
  </tr>
  <tr>
  </tr> 
</table>
</div>
<div class="header">

<table  width="100%">
  <tr>
    <td colspan="3" class="titulo">REGISTRO DE INVENTARIO PERMANENTE VALORIZADO</td>
  </tr>
</table>

<table id="header" width="100%">

  <tr>
    <td width="20%"><span class="campo"> PERIODO </span>  </td>
    <td width="20%"> {{ $periodo }} </td>
    <td width="60%"></td>    
  </tr>
  
  <tr>
    <td><span class="campo"> RUC:</span> </td>
    <td> {{ $empresa['ruc'] }}</td>
    <td></td>    
  </tr>

  <tr>
    <td><span class="campo"> RAZON SOCIAL:</span> </td>
    <td> {{ $empresa['nombre'] }}</td>
    <td></td>    
  </tr>

  <tr>
    <td><span class="campo"> ESTABLECIMIENTO:</span> </td>
    <td> {{ $nombre_local }}</td>
    <td></td>    
  </tr>

 <tr>
    <td><span class="campo"> METODO DE VALUACION:</span> </td>
    <td> PROMEDIO PONDERADO </td>
    <td></td>    
  </tr>


</table>
</div>
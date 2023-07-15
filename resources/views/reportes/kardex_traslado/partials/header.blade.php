<div class="header">

  <table width="100%">
    <tr>
      <td colspan="3" class="titulo"> <h2 style="padding:0;margin:0"> {{ $title }} </h2></td>
    </tr>
  </table>

  <table id="header" width="100%">

    <tr>
      <td><span class="campo"> RUC:</span> </td>
      <td> {{ $ruc  }}</td>
      <td></td>
    </tr>

    <tr>
      <td><span class="campo"> RAZON SOCIAL:</span> </td>
      <td> {{ $razon_social }}</td>
      <td></td>
    </tr>

    <tr>
      <td><span class="campo"> ESTABLECIMIENTO DE ORIGEN: </span> </td>
      <td> {{ $local_origen->LocNomb }} - {{ $local_origen->LocDire }}</td>

      <td></td>
    </tr>

    <tr>
      <td><span class="campo"> ESTABLECIMIENTO DE DESTINO : </span> </td>
      <td> {{ $local_destino->LocNomb }} - {{ $local_destino->LocDire }}</td>


      <td></td>
    </tr>

    <tr>
      <td><span class="campo"> FECHA INICIO:</span> </td>
      <td> {{ $fecha_inicio }} </td>
      <td></td>
    </tr>

    <tr>
      <td><span class="campo"> FECHA FINAL:</span> </td>
      <td> {{ $fecha_final }} </td>
      <td></td>
    </tr>

  </table>
</div>

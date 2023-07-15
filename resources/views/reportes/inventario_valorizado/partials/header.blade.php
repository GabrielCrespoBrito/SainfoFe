<div class="container header">
<table id="header" width="100%">
  <tr>
    <td  colspan="2" class="titulo text-center">  Reporte de Inventario Valorizado </td>
  </tr>

  <tr>
    <td> {{ $nombre_empresa }} </td>
    <td style="text-align:right"> <strong> Fecha: </strong> {{ date('Y-m-d')  }} </td>
  </tr>

  <tr>
    <td> {{ $ruc_empresa }} </td>
    <td> </td>
  </tr>

  <tr>
    <td style="text-align: center; font-size: .8em" colspan="2">
      Almacen: <strong style="display:inline-block;margin-left:20px;margin-right:30px"> {{ $data_report['local_nombre'] }} </strong>
      Moneda <strong style="display:inline-block;margin-left:20px;margin-right:30px"> {{ $data_report['moneda_nombre'] }} </strong> 
      TIPO EXISTENCIA <strong style="display:inline-block;margin-left:20px"> {{ $data_report['tipo_existencia_nombre'] }} </strong> 
    </td>
  </tr>

</table>
</div>
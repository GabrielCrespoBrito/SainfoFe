<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta charset="UTF-8">
  <title>Reporte</title>
  <style>
    body {
      font-family: 'Arial Narrow' !important;
      /* font-size: .6em; */
      font-style: normal;
      font-weight: normal;
      margin: 0;
    }

    table {
      width: 100% !important;
      page-break-inside: avoid;
    }

    /* ---------- header ---------- */

    .header {
      width: 100%;
    }

    .container {
      /*outline: 3px solid blue;  */
      width: 100%;
    }

    .header td {
      padding: 0 10px;
    }

    .text-right {
      text-align: right;
    }

    /* titulo */
    .header .titulo {
      font-size: 1.5em;
      color: #000;
      text-align: left;
      border-bottom: 1px solid #000;
      padding: 3px 0;
      font-weight: bold;
    }

    .header .campo {
      font-weight: bold;
    }

    /* ---------- /header ---------- */

    /* ---------- items ---------- */

    .div_items {
      overflow: hidden;
      margin-top: 10px;
    }

    table {}

    .table_items {
      border-collapse: collapse;
    }

    .table_items .thead {}

    .table_items .tr-header td {
      border: 2px solid #000;
      padding: 10px;
      text-align: center;
    }

    .table_items .tr-subheader td {
      border-bottom: 2px solid #000;
      padding: 10px;
      text-align: center;
    }


    .border-left {
      border-left: 2px solid #000;
    }

    .border-right {
      border-right: 2px solid #000;
    }


    .table_items .tr_producto {

      background-color: #cccccc
    }


    .table_items .tr_producto .td_info {
      padding-top: 10px;
    }

    .table_items .tr_producto .value {
      font-weight: bold;
    }

    .table_items .tr_producto .propiedad {}

    .table_items .tr_descripcion td {
      text-align: center;
    }

    .table_items .tr_descripcion td {
      border-bottom: 1px dashed #999;
    }

    .table_items .tr_descripcion.total td {
      border-bottom: 1px solid #000;
      border-top: 1px solid #000;
      padding: 5px 0;
      font-weight: bold
    }

    .table_items .thead {}

    .table_items .tr-header td {
      border: 2px solid #000;
      padding: 10px;
      text-align: center;
    }

    .table_items .tr-subheader td {
      border-bottom: 2px solid #000;
      padding: 10px;
      text-align: center;
    }


    .border-left {
      border-left: 2px solid #000;
    }

    .border-right {
      border-right: 2px solid #000;
    }


    .table_items .tr_producto {

      background-color: #cccccc
    }


    .table_items .tr_producto .td_info {
      padding-top: 10px;
    }

    .table_items .tr_producto .value {
      font-weight: bold;
    }

    .table_items .tr_producto .propiedad {}

    .table_items .tr_descripcion td {
      text-align: center;
    }

    .table_items .tr_descripcion td {
      border-bottom: 1px dashed #999;
    }

    .table_items .tr_descripcion.total td {
      border-bottom: 1px solid #000;
      border-top: 1px solid #000;
      padding: 5px 0;
      font-weight: bold
    }

    .item-totales td {
      border-top: 1px solid black;
      font-weight: bold;
    }

    tr.total {
      background-color: #cccccc
    }
  </style>
</head>

<body>
  <div class="container" style="font-family: 'Arial Narrow' !important">
    
    {{--  --}}
    <table width="100%" style="padding-bottom: 10px; margin-bottom:20px">
      
      <tr>
        <td class="text-right"> {{ date('Y-m-s') }} </td>
      </tr>

      <tr>
        <td> EMPRESA: <strong> {{ $empresa->EmpNomb }} </strong> </td>
      </tr>

      <tr>
        <td> RUC: <strong> {{ $empresa->EmpLin1 }} </strong> </td>
      </tr>

    </table>
    {{--  --}}
    
    <table class="table_items">
      <thead class="header">
        <tr class="tr-header">
          <td class="">ORDEN</td>
          <td class="">CODIGO</td>
          <td class="">PRODUCTO</td>
          <td class="text-right">CANT.</td>
          <td class="">EMITIDO</td>
          <td class="text-center">CULMINADO</td>
          <td class="">USUARIO</td>
          <td class="">RESP.</td>
          <td class="text-right">ESTADO</td>
        </tr>
      </thead>
      <tbody>
        @foreach( $producciones as $produccion )
        <tr>
          <td>{{ $produccion->manId  }} </td>
          <td>{{ $produccion->manCodi  }} </td>
          <td>{{ $produccion->manNomb  }} </td>
          <td class="text-right" style="padding-right:10px"> {{ $produccion->manCant  }} </td>
          <td>{{ $produccion->manFechEmis  }} </td>
          <td class="text-center">{{ $produccion->manFechCulm  }} </td>
          <td>{{ $produccion->USER_CREA  }} </td>
          <td>{{ $produccion->manResp  }} </td>
          <td class="text-right" style="text-transform:uppercase"> {{ $produccion->presenter()->getReadEstado() }} </td>
        </tr>
        @endforeach
      </tbody>

    </table>

  </div>
</body>

</html>
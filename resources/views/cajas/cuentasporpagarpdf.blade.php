<!DOCTYPE html>
<html lang="en">
<style>
</style>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta charset="UTF-8">
  <title>Reporte</title>
  <style>
    body {
      font-size: .7em;
      font-family: 'Arial Narrow' !important;
      letter-spacing: 1px;
    }

    /* ---------- header ---------- */

    .header {
      width: 100%;
      border-bottom: 2px solid #ccc;
    }

    .header td {
      padding: 0 10px;
    }

    /* titulo */
    .header .titulo {
      font-size: 1.5em;
      color: #999;
      text-align: center;
      padding: 3px 0;
      font-weight: bold;
      border-bottom: 2px solid #ccc;
    }

    .header .campo {
      font-weight: bold;
    }

    /* ---------- /header ---------- */


    /* ---------- items ---------- */

    .div_items {
      margin-top: 10px;
    }

    .div_items .table_items {
      border-collapse: collapse;
    }


    .div_items .table_items .thead {}

    .div_items .table_items .thead td {
      border-top: 2px solid #ccc;
      border-bottom: 2px solid #ccc;
      padding: 10px 0;
      background-color: #fafafa;
      color: black;
      text-align: center;
    }

    .div_items .table_items .documento_tr,
    .div_items .table_items .totales_tr td {
      padding: 10px 0
    }

    .div_items .table_items .totales_tr:last-child td {
      border-bottom: 1px solid #999;
    }

    .div_items .table_items .documento_tr td {
      font-weight: bold;
      text-align: left;
      padding: 10px 10px;
      background-color: #ccc;
      text-align: center;
    }

    .div_items .table_items .totales_tr td .campo {
      font-weight: bold;
    }

    .div_items .table_items .totales_tr td .value {
      /*font-weight: bold;*/
    }


    .tipo_documento_id,
    .value_total {
      border: 1px solid #000;
      margin-left: 10px;
      padding: 0 3px;
      border-radius: 20px;
    }

    .body_tr td {
      border-bottom: 1px solid #999;
      border-right: 1px solid #999;
      border-left: 1px solid #999;
      text-align: center;
    }

    .body_tr td:first-child {
      border-left: none;
    }

    .body_tr td:last-child {
      border-right: none;
    }

    .tr_info td {
      border-right: 1px solid #ccc !important; 
    }

    /* body_tr_item */

    .body_tr_item td {
      /*outline: 1px solid red;*/
    }

    .strong {
      /*font-weight: bold;*/
    }

    .tr_item-head td {
      /*border-top: 1px solid #ccc;*/
      background-color: #fafafa;
    }

    /* ---------- items ---------- */

    .s {
      font-weight: bold;
    }


    .tr-bottom td {
      border-bottom: 2px dotted #666;
      border-top: 2px dotted #666;

    }

    tr.totales td {
      outline: 1px solid red;
    }

    .table_data {
      border-collapse: collapse;
    }

    .table_data .header th {
      border-bottom: 1px solid #999;
      border-top: 1px solid #999;
      border-right: 1px solid #ccc;
      border-left: 1px solid #ccc;
      
    }

    .table_data td,
    .table_data th {
      vertical-align: top;
      /*
      white-space: pre-wrap;
      overflow: hidden;
      */
      padding: 0 2px;
      text-align: left;
    }


    td.border-right {
    }

    .tr_info td {
      border-bottom: 1px dotted #999;
      border-left: 1px solid #ccc;
      padding-top: 3px;
      padding-bottom: 3px;

    }

    .text-right {
      text-align: right !important;
    }

    .table_data .totales td {
      outline: 1px solid #ccc !important;
      background: #ccc;
    }
  </style>
</head>

<body>
  <div class="container" style="font-family: 'Helvetica' !important">
    <h1> {{ $title }} </h1>
    @php
    $empresa = get_empresa();
    @endphp
    <table width="100%">
      <tr>
        <!--  -->
        <td>
          <p>{{ $empresa->EmpNomb }}</p>
          <p>{{ $empresa->EmpLin1 }}</p>
        </td>
        <!--  -->
        <!--  -->
        <td style="text-align: right">
          <p> Fecha {{ hoy()  }} </p>
          <p> Hora: {{ date('H:i:m')  }} </p>
        </td>
        <!--  -->
      </tr>
    </table>
  </div>

  <div class="container">
    @include('cajas.partials.reporte_porpagar.table_2')
  </div>

</body>

</html>
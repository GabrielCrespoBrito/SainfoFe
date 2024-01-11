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
      font-size: .6em;
      letter-spacing: 0px;
      width: 100%;
      margin: 0 auto;
      padding: 0;
    }

    table {}


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
      /*outline: 3px solid red;*/
      margin-top: 10px;
    }

    .div_items table {}

    .div_items .table_items {
      border-collapse: collapse;
    }


    .table_items .thead {}

    .table_items .tr-header td {
      border: 1px solid #000;
      padding: 10px;
      text-align: center;
    }

    .table_items .tr-body td {
      border: 1px solid #cccccc;
      padding: 3px;
      text-align: center;
    }

    

    .table_items .tr-subheader td {
      padding: 10px;
      text-align: center;
    }


    .border-left {
      border-left: 1px solid #000;
    }

    .border-right {
      border-right: 1px solid #000;
    }


    .table_items .tr_producto {

      background-color: #cccccc
    }


    .table_items .tr_producto .td_info {
      padding-top: 5px;
    }

    .table_items .tr_producto .value {
      font-weight: bold;
    }

    .table_items .tr_producto .propiedad {}

    .table_items .tr_descripcion td {
      text-align: center;
    }

    .table_items .tr_descripcion td {
    }

    .table_items .tr_descripcion.total td {
      padding: 5px 0;
      font-weight: bold
    }
    
  </style>
</head>
<body>
  <div class="container" style="font-family: 'Helvetica' !important">
    @include('reportes.productos_stock_min.partials.header')
    @include('reportes.productos_stock_min.partials.body',['items' => $data])
  </div>
</body>
</html>
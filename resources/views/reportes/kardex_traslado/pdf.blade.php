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

    .div_items .table_items {
    border: 1px solid black;
    }


    .border-right {
    border-right: 1px solid black;
    }

    .border-left {
    border-right: 1px solid black;
    }
    .border-top {
    border-top: 1px solid black;
    }

    .border-bottom {
    border-bottom: 1px solid black;
    }


  .table_descripcion .titulo {
    font-weight:bold;
    font-size: 1.1em;
  }

  .table_descripcion .propiedades {
    display: block;
    overflow:hidden;
    position: relative;
  }

  .table_descripcion .propiedades .nombre,
  .table_descripcion .propiedades .valor {
    display: block;
  }

  .table_descripcion .propiedades .nombre,
  .table_descripcion .propiedades .valor {
    float: left;
  } 


  .table_descripcion .propiedades .nombre {
    width: 30%;
  }

  .table_descripcion .propiedades .valor {
    font-weight:bold;
    width: 70%;

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

  .text-right {
    text-align: right
  }

  .text-left {
    text-align: left
  }

  .text-bottom {
    text-align: bottom
  }

  .text-top {
  text-align: top
  }

  .tr-border-top td {
    border-top: 1px solid black;
  }

/*
  .barraseparadora {
    position :absolute;
    top: 20%;
    right: 0;
    font-size: 3em;
    font-weight:bold;
    color: gray;
  }
*/

  </style>
</head>
<body>
  <div class="container" style="font-family: 'Helvetica' !important">
    @include('reportes.kardex_traslado.partials.header')
    @include('reportes.kardex_traslado.partials.table')
  </div>
</body>
</html>

<!DOCTYPE html>
  <html lang="en">
  <style>
  </style>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta charset="UTF-8">
  <title> {{ $reporte_titulo }} </title>
  <style>

  body 
  {
    font-size: .7em;
    font-family: 'Arial Narrow' !important;
    letter-spacing: 0px;
  }

  .text-uppercase {
    text-transform: uppercase;
  }

	.bold ,
	.strong {
		font-weight: bold;
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


  .div_items .table_items .thead {
  }

  .div_items .table_items .thead td {
    border-top: 2px solid #ccc;
    border-bottom: 2px solid #ccc;    
    padding: 10px 0;
    background-color: #fafafa;
    color: black;
    text-align: center;
  }

  .div_items .table_items .documento_tr , .div_items .table_items .totales_tr td {
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


  .tipo_documento_id , .value_total {
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

  /* body_tr_item */

  .body_tr_item  td{
    /*outline: 1px solid red;*/
  }

  .strong {
    /*font-weight: bold;*/
  }

  .tr_item-head  td {
    /*border-top: 1px solid #ccc;*/
    background-color: #fafafa;
  }

  /* ---------- items ---------- */

  .s {
    font-weight: bold;
  }

  .tr-bottom td {
    border-bottom: 2px dotted #666;    
  }

  .no-bold {
    font-weight: 100;
  }

  .table.border  td {
    border: 1p solid gray;
  }

  .text-center {
    text-align: center;
  }
  
.text-right {
    text-align: right;
  }  
  .bg-gray {
    background-color: #ccc;
  }

  .caja_informacion td {
    padding: 0;
    margin: 0;
    line-height: 1em;
  }

  body {
    margin : 0;
    padding: 0;
  }

  </style>
  </head>
  <body>

    <table class="caja_informacion" width="100%">

      <tr class="tr-bottom">
        <td>
          <h1 style="line-height:1em" class="text-uppercase">{{ $reporte_titulo }}</h1>
        </td>
      </tr>

      <tr class="tr-bottom">
        <td>
          <h2>CAJA</h2>
          <h2 class="no-bold">{{ $caja_nombre }}</h2>
        </td>
      </tr> 

      <tr class="tr-bottom">
        <td>
          <h2>ESTADO</h2>
          <h2 class="no-bold">{{ $estado }}</h2>
        </td>
      </tr>

      <tr class="tr-bottom">
        <td>
          <h2>USUARIO</h2>
          <h2 class="no-bold">{{ $usuario }}</h2>
        </td>
      </tr>

      <tr class="tr-bottom">
        <td>
          <h2>FECHA APERTURA</h2>
          <h2 class="no-bold">{{ $fecha_apertura }}</h2>
        </td>
      </tr>

      <tr class="tr-bottom">
        <td>
          <h2>FECHA CIERRE</h2>
          <h2 class="no-bold">{{ $fecha_cierre }}</h2>
        </td>
      </tr>

      <tr class="tr-bottom">
        <td>
          <h2>SALDO APERTURA</h2>
          <h2 class="no-bold">S/. {{ decimal($saldo_apertura, 2) }}</h2>
        </td>
      </tr>
      
      <tr class="tr-bottom">
        <td>
          <h2> INGRESOS </h2>
          <h2 class="no-bold">S/. {{ decimal($ingresos, 2) }}</h2>
        </td>
      </tr>

      <tr class="tr-bottom">
        <td>
          <h2> SALIDAS </h2>
          <h2 class="no-bold">S/. {{ decimal($salidas, 2) }}</h2>
        </td>
      </tr>

      <tr class="tr-bottom">
        <td>
          <h2> TOTAL VENTAS </h2>
          <h2 class="no-bold">S/. {{ decimal($total_ventas, 2) }}</h2>
        </td>
      </tr>

      <tr class="tr-bottom">
        <td>
          <h2> PAGO EFECTIVO </h2>
          <h2 class="no-bold">S/. {{ decimal($pago_efectivo, 2) }}</h2>
        </td>
      </tr>

      <tr class="tr-bottom">
        <td>
          <h2> PAGOS OTROS DIAS </h2>
          <h2 class="no-bold">S/. {{ decimal($pago_cobranza, 2) }}</h2>
        </td>
      </tr>

      

      <tr class="tr-bottom">
        <td>
          <h2> SALDO </h2>
          <h2 class="no-bold">S/. {{ decimal($saldo, 2) }}</h2>
        </td>
      </tr>

    </table>


    <table class="table border" width="100%">
      
      <tr class="tr-bottom bg-gray">
        <td colspan="2">
          <h2 class="text-uppercase text-center"> RESUMEN DE METODOS DE PAGO </h2>
        </td>
      </tr>

      <tr class="tr-bottom">
        <td> <h2>Forma de Pago</h2></td>
        <td class="text-right"> <h2>Total</h2> </td>
      </tr>

      @foreach( $metodos_pagos as $metodo_pago )
        <tr class="tr-bottom">
          <td> <h2 class="no-bold"> {{ $metodo_pago['nombre'] }} </h2></td>
          <td class="text-right"> <h2 class="no-bold"> S/. {{ decimal($metodo_pago['total']) }} </h2> </td>
        </tr>
      @endforeach

    </table>

  </body>
</html>
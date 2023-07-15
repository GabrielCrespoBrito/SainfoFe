<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ticket</title>
  <style>

    * {
      padding: 0;
      margin: 0;
    }


    body {
      margin: 0;
      padding: 0;
    }

    .container-ticket {
      padding: 0;
      margin: 0 auto;
      margin-top: 1em;
      font-size: .7em;
      font-family: "Helvetica" !important;
    }

    .pie {}

    .top 
		{
      padding-top: 1pt;
      box-sizing: border-box;
    }

    .titulo 
		{
      font-size: 1.5em;
    }

    .container-ticket 
		{
      padding: 10pt 7pt;
    }

    .center 
		{
      text-align: center;
    }

    .left 
		{
      text-align: left;
    }

    .right 
		{
      text-align: right;
    }

    p 
		{
      padding: 0;
    }

    .size-regular {
      /*font-size: .8em;*/
    }

    .border-separador {
      /* margin: 10pt 0; */
      padding: 0 0;
      border-bottom: 1px dotted #333333;
    }

    .span {
      display: block;
    }

    .o {
      outline: 1px solid black;
    }

    .letra_peq {
      /*font-size: .9em;*/
    }

  </style>
</head>
<body>

  <div class="container-ticket">

    @include('ventas.partials.pdf.ticket.cabezera')
    <p class="border-separador"></p>
    @include('ventas.partials.pdf.ticket.cliente')
    
		@if( $venta2->isNotaCredito() || $venta2->isNotaDebito() )
			<p class="border-separador"></p>
			@include('ventas.partials.pdf.ticket.data_factura')
    @endif

    <p class="border-separador"></p>
    @include('ventas.partials.pdf.ticket.items')
    <p class="border-separador"></p>
    @include('ventas.partials.pdf.ticket.totales')
    @include('ventas.partials.pdf.ticket.pie')

  </div>
</body>
</html>

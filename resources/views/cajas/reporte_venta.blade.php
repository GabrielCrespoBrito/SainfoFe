<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title> {{ $nombreDocumento }} </title>
</head>

<style type="text/css">

	html {
	}

	body {
		margin: 0 auto;
    letter-spacing: 0px;
		font-family: 'Arial Narrow';
	}


	.bold ,
	.strong {
		font-weight: bold;
	}

  .text-right {
    text-align: right;
  }

 .text-left {
    text-align: left;
  }

 .text-center {
    text-align: center;
  }

 .text-justify {
    text-align: justify;
  }  

  .text-uppercase {
    text-transform: uppercase;
  }

	/* Table */

	.table_items {
	}


	.table_items {
		width: 100%;;
		border-collapse: collapse;
		/* white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;		 */
	}

	.table_items td, 
	.table_items th 
	{
		padding: 2px;
	}

	/* thead */
	.table_items thead {
		width: auto;
	}


	.table_items .thead td {
		border-bottom: 1px solid #999;		
		border-top: 1px solid #999;		
	}


	/* thead */

	.table_items .tr-nombre td {
		font-weight: bold;
	}




	/* tr de totales */

	.table_items .total-tr td {
		background-color: #ebe9e9;
	}

	.table_items .total-tr td:first-child {
		background-color: #ebe9e9;
		border-left: 1px solid #999;
	}

	.table_items .total-tr td:last-child {
		border-right: 1px solid #999;
	}


	.table_items .total-tr.totalNombre td {
		border-bottom: 1px solid #999;
		border-top: 1px solid #999;		
	}

	.table_items .total-tr.valores.ultimo td {
		border-bottom: 1px solid #999;
	}

	.table_items .total-tr.totalNombre td:first-child {
	}


	.table_items .total-tr.global td{
	

	}
	.table_items .total-tr.global.valores td{
		font-weight: bold;
	}


	/* tr de totales */





	.table_items .total-tr.total_table td {	
	}

	/* tr de totales */


	.documento_tr td {
		/*background-color: #ccc;*/
		font-weight: bold;
		text-align: left;
	}


</style>
<body>

@include('cajas.partials.reporte_ventas.header')
@include('cajas.partials.reporte_ventas.table')

</body>
</html>

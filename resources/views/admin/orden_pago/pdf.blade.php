
<!DOCTYPE html>
<html lang="en">
<style>
</style>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta charset="UTF-8">
	<title></title>
	<style>

	html {
		padding: 10px;
	}

	body {
		overflow: hidden;
		width: 100%;
		margin:  0 auto;
		/* outline: 1px solid red */
	}
	
	.container {
		font-size: 12px;
	}

  .condicion_tr {
	}


.border-top {
	border-top: 1px solid #666;	
}

.border-bottom {
	border-bottom: 1px solid #666;}

body { 
font-family: Arial !important; 
text-align: left;
/*
margin:0;
margin:0;
margin-left: .5cm;
margin-right: -.5cm;			
*/
}

.t_cabezera {
margin-bottom: 5px;
border-collapse: collapse;
}

.t_cabezera .data_1 {
width: 70%;
}

.data_1_1 {
}

.t_cabezera .empresa_nombre {
color: #ff8000;
font-weight: bold;
font-size: 1.5em;
padding: 0;
margin: 5px 0;
}

.t_cabezera .empresa_nombre_subtitulo {
font-weight: bold;
font-size: 1.1em;
}		

.t_cabezera .data_1 .img_logo{
	width: 150px;
	max-width: 200px;
	height: 60px;
	max-height: 80px;    	
}

.t_cabezera .data_1 .img_logo2{
width: 250px;
max-width: 350px;
height: 60px;
max-height: 80px;
}


.total_le {
padding-left: 10px;
}


.t_cabezera .data_1 .data_1_1 {
width: 30%;
}	

.t_cabezera .data_1 .data_1_2 {
width: 70%;
}		

.t_cabezera .direccion,.telefono,.email {
padding: 0;
margin: 0
}


/* informacion factura  */
.t_cabezera .data_2{
width: 30%;			
text-align: center;
border: 1px solid black;
border-radius: 20px
}


.t_cabezera .empresa_ruc {
font-weight: bold;
font-weight: bold;
font-size: 1.3em;
}		

.t_cabezera .factura_titulo {
color: #ff8047;
font-weight: bold;
font-size: 1.3em;
}		

.t_cabezera .factura_numero {
font-weight: bold;
font-size: 1.3em;
}						


.text-r {
text-align: right;
}

/* cliente  */

.cliente {
border-collapse: collapse;
}

.cliente .data_1, .cliente .data_2 {
border: 1px solid black;
padding: 5px 10px;
}


.cliente .cliente_factura,
.cliente .doc_interno {
display: inline-block;
border: 1px solid #666666;
padding: 10px;
height: 80px;
border-radius: 10px;
}

.cliente .cliente_factura {
width: 70%;
}



.cliente .cliente_tipodato {
font-weight: bold;		
}

/* info pago */
.info_pago {
border-collapse: collapse;
}

.data_pago {
border: 1px solid black;
padding: 5px;
}

.data_pago .cliente_tipodato {
font-weight: bold;	
}

.data_pago .cliente_dato , .data_pago .cliente_tipodato {
	display: inline;
}



.items {
position: relative;
top: -90px;
}

.table_factura {
text-align: center;
width: 100%;
border-collapse: collapse;
border: 1px solid black;
}

.table_factura thead {
background-color: black;
color: white;
}

.table_factura tbody td{
}

.table_factura td.total-importe {
	border-top: 1px solid black;
	text-align: center;
}


.items_data {

}

/*pie*/

.row_pie {
	/*overflow: hidden;*/
	width: 100%;
/*	position: absolute;
	bottom: 0;
	padding: 0;
	margin: 0;*/
}

.referencia{	
border-top: 1px solid #ccc;
}

.referencia table {	
}

.nombre_ref {
	display: inline-block;
	text-align: left;
	margin-top: .2em;
	font-weight: bold;
}

.valie_ref {
	display: inline-block;
	text-align: right;
}


.pie {
width: 100%;
/*border-top: 1px solid #ccc;*/
}

.pie .data_1 {
padding-left: 30px;
}

.pie .data_2 {
border-left: 1px solid #999;
}

.pie .items_resumen ,
.pie .items_totales {
padding: 0;
display: inline-block;
}

.pie .items_resumen p {
margin: 0;
padding: 0;
}

.lipo  {
outline: 1px solid black;
}

.text-c {
text-align: center;
}

.table_totales {
border-collapse: collapse;
}


.table_totales  td {
padding: 5px 0;
}
.table_totales  td:first-child {
}

.table_totales .mon {
line-height: -10px;
margin: 100%;
vertical-align: baseline;
position: relative;
top: -20px;
}

.table_totales .total_cifra {
text-align: right;
margin-top: 5px;
padding-right: 10px;
position: relative;
top: 0px;
margin-top:10px;
}
.table_totales .nombre_total {
padding-left: 10px;
}

.row_totales {
}

.title_c {
	margin-bottom: 5px;
	font-weight: 600;
}

.totales_cotizacion {
	width: 100%;
	font-weight: bold;
}

.totales_cotizacion .valor{
	font-weight: none;
}

.totales_cotizacion * {
}


/* Table Cliente */

.div_cliente {
	border: 1px solid black;
	width: 100%;
	margin-bottom: 5px;
}

.table_cliente {
}


.table_cliente .info-nombre {
	font-weight: bold;
	text-transform: uppercase
}

.table_cliente .info-valor {
}


.table_cliente {
	border-collapse: collapse;
}


.table_cliente td {
	border: 1px solid black;
}

.table_cliente p {
	padding: 2px;
	margin:0;
}


.table_factura td.total-importe {
	border: 1px solid black;
}

.table_factura td.total-importe {
	border: 1px solid black;
}

.table_factura td.cuentas {
	text-align: left !important;
	border: 1px solid black;
}

.table_factura td.cuentas .title {
	margin-left: 20px;
	font-weight: bold;
}

.table_factura td.cuentas .cuenta {
	margin-left: 20px;
}

.table_factura td.instruccion {
	border: 1px solid black;
	text-align: center;
}



.table_cliente p {
}


/*  */

.table_header {
	width: 100%;
}

.table_header  {
}

.table_header  {
}


.table_header .box-nro-documento  {
    border: 1px solid black;
    text-align: center;	
		width: 100%;
    margin:0;
}


.table_header .box-nro-documento .data  {	
	font-weight: bold;
}


.table_header .td-empresa-nombre  {	
}

.table_header .td-empresa-nombre p.empresa_nombre  {	
	padding: 0;
	margin: 0 0 10px;
}

.table_header .td-empresa-nombre p.empresa_nombre img  {	
max-height: 100px;
    max-width: 200px;
}


.table_header .td-nro-documento  {
	vertical-align: top !important;	
}




.table_header .td-nro-documento .data  {
	font-size: 2em;margin: 10px 0;
	}

.table_header .td-nro-documento .ruc  {
	font-weight: 400;
	}


.table_header .td-nro-documento .nombre-docummento  {
	color: orange;
}



.table_header .td-nro-documento .nombre-documento  {
	color: orange;
}

.table_header .td-nro-documento .correlativo-documento  {
	font-weight: 500;
	}



.table_header .td-nro-documento .correlativo-documento  {

}
/*  */

</style>
</head>
<body>
  {{-- @dd( $codigo ) --}}
  <div class="container" style="font-family: 'Helvetica' !important">		
		@include('orden_pago.partials.pdf.header')
		@include('orden_pago.partials.pdf.cliente')
		@include('orden_pago.partials.pdf.table')
	</div>		
	</div>
</body>
</html>
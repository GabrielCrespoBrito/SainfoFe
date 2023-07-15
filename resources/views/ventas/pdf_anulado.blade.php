<!DOCTYPE html>
<html lang="en">
<style>


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


.data_pago .cliente_dato{

}

.data_pago .cliente_tipodato {
font-weight: bold;	
}




.items {
position: relative;
top: -90px;
}

.table_factura {
text-align: center;
width: 100%;
border-collapse: collapse;
}

.table_factura thead {
background-color: black;
color: white;
}

.table_factura tbody td{
}

.table_factura tbody td {
}




.items_data {

}

/*pie*/

.row_pie {
overflow: hidden;
width: 100%;
position: absolute;
bottom: 0;
/*height: 230px;;*/
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
border-top: 1px solid #ccc;
}

.pie .data_1 {
padding-left: 0px;
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

</style>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta charset="UTF-8">
	<title> {{ $title }} </title>
	<style>
		.container {
			font-size: 12px;
			font-family: 'Helvetica';
		}
	</style>
</head>
<body>
	<?php $is_boleta = false; ?>
	<div class="container" style="font-family: 'Helvetica' !important ">
    @include('ventas.partials.pdf.a4.cabezera')
		@include('ventas.partials.pdf.a4.cliente_anulado')
	</div>
</body>
</html>
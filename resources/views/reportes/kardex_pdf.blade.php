<!DOCTYPE html>
<html lang="en">
<style>
</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta charset="UTF-8">
<title>Reporte</title>
<style>

body { 
  font-family: 'Arial Narrow' !important; 
  font-size: .6em;
  font-style: normal;
  font-weight: normal;
  margin: 0;
}

table{
  width:100% !important; 
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

.div_items  table {
}

.div_items .table_items {  
  border-collapse: collapse;
}

.table_items .thead {
}

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

.table_items .tr_producto .propiedad { 
}

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

.table_items .thead {
}

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

.table_items .tr_producto .propiedad { 
}

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
    @include('reportes.partials.pdf.kardex_fisico.header')
    @include('reportes.partials.pdf.kardex_fisico.table_items')
  </div>
</body>
</html>
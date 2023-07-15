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
  font-size: .7em;
  font-family: Arial !important;
  letter-spacing: 0px;
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


</style>
</head>
<body>
  <div class="container" style="font-family: 'Helvetica' !important">    

  @include('reportes.partials.pdf.documentos_faltantes.header')
  @include('reportes.partials.pdf.documentos_faltantes.table_items')

  </div>
</body>
</html>



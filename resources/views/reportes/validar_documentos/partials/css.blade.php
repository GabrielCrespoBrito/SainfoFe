html {
  padding: 0;
  margin: 17px 25px;
}




body {
  /*font-family: 'monospace' !important; */
  font-family: 'Arial narrow' !important; 
  font-style: normal;
  font-weight: normal;
  margin: 0;
  padding: 0px;
  margin: 0;
  /*outline: 2px solid red;*/
}

.text-right {
  text-align: right !important;
}

.text-left {
  text-align: left !important;
}

.text-center {
  text-align: center !important;
}

/* Table inventario */

.table-inventario .tr-header {
  font-weight: bold;
}

.table-inventario tr-header .td-codigo {
}

.table-inventario .tr-total {
  font-weight: bold;
}

.table-inventario .tr-total .td-total {
  border-bottom: 1px solid black;
  font-size: 1.1em
}

.table-inventario .tr-total-general {
  font-weight: bold;
}

.table-inventario .tr-total-general td {
  padding-top:10px;
}

.table-inventario .tr-total-general .tr-text  {
  border-bottom: 1px dotted black;
}
.table-inventario .tr-total-general .tr-total  {
  border-bottom: 1px dashed black;
  font-size: 1.1em
}



.header {
  text-transform: uppercase;
  padding-bottom: 5px;
  margin-bottom: 5px;
  margin-bottom: 5px;
}

.header .titulo{
  font-weight: bold;
}

.table-items {
  border-collapse: collapse;
  font-size: .6em;
  word-spacing: 1px;
  table-layout: fixed;
}

.table-items thead td {
  border: 1px solid black;
  padding: 1px 0;
  vertical-align: middle;
  text-align: center;
  text-transform: uppercase;
}


.table-items {
 border-collapse: collapse;
}

.table-items tbody tr.tipo-documento td {
  font-weight: bold;
  padding: 5px 5px;
  background-color: #d8d8d8;
}

.table-items tbody tr.total-documento td {
  font-weight: bold;
}

.table-items tbody tr.total-documento td.value {
  text-align: right;
  border-bottom: 1px solid black;
  border-top: 1px solid black;  
}

.table-items tbody td {
  letter-spacing: 0;
  word-spacing: 0;
  text-align: left;
  text-transform: uppercase;
  white-space: nowrap;
  overflow: hidden;
  padding-top: 3px;
  padding-bottom: 3px;  
  text-overflow: clip;    
}


.table-inline-report td,
.table-inline-report th {
  padding: 0 3px !important;
}
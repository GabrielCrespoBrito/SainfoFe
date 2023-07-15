.page-break {
  page-break-after: always;
}

@if($logoMarcaAgua)
body {
  background-image:url("data:image/png;base64,{{$logoMarcaAgua}}");
  background-repeat: no-repeat;
  background-position: center 60%;
  background-attachment: fixed;
  background-size: 100%;
  opacity: .3;
}
@endif

.aa {
  height: 75%;
  border-right: 1px solid black;
  border-left: 1px solid black;
}

.small .aa {
  height: inherit;
  border-right: none;
  border-left: none;
}

body {
  padding-top: 0;
}


.container {
  font-size: 11px;
}

.container.small {
  font-size: 8px;
}


.t_cabezera .showAll {
  width: 400px;
  text-align: center;
}

.small .t_cabezera .showAll {}

.condicion_tr {
  border-bottom: 2px solid #000;
}

.condicion_tr .td_ele {
  border-top: 1px solid #666;
}

body {
  font-family: Arial !important;
  text-align: left;
  margin: 0;
  margin: 0;
  margin-left: .5cm;
  margin-right: -.5cm;
}

.t_cabezera {
  margin-top: 0;
  margin-bottom: 0px;
  border-collapse: collapse;
}

.t_cabezera .data_1 img {
  width: 100%;
  /* outline: 3px solid red; */
}

.data_1_1 {}

.t_cabezera .empresa_nombre {
  color: #ff8000;
  font-weight: bold;
  font-size: 1.5em;
  padding: 0;
  margin: 0 0 5px;

}

.t_cabezera .empresa_nombre_subtitulo {
  font-weight: bold;
  font-size: 1.1em;
}

.t_cabezera .data_1 .img_logo {
  width: 150px;
  max-width: 200px;
  height: 60px;
  max-height: 80px;
}

.t_cabezera .data_1 .img_logo2 {
  width: 250px;
  max-width: 350px;
  height: 60px;
  max-height: 80px;
}


/* Pequeño */
.small .t_cabezera .data_1 .img_logo {
  width: 100px;
  max-width: 150px;
  height: 30px;
  max-height: 40px;
}

.small .t_cabezera .data_1 .img_logo2 {
  width: 1250px;
  max-width: 175px;
  height: 30px;
  max-height: 40px;
}


.observacion {
  /* outline: 3px solid red; */
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

.t_cabezera .direccion,
.telefono,
.email {
  padding: 0;
  margin: 0
}

/* Informacion Factura  */
.t_cabezera .data_2 {
  width: 30%;
  text-align: center;
}

/* Informacion Factura  */
.small .t_cabezera .data_2 {
  /* border: 1px solid yellowgreen; */
}

.border {
  width: 100%;
  border: 1px solid black;
  border-radius: 10px
}


.t_cabezera .empresa_ruc {
  font-weight: bold;
  font-weight: bold;
  font-size: 1.3em;
  line-height: .8em;
}

.t_cabezera .factura_titulo {
  color: #ff8047;
  font-weight: bold;
  font-size: 1.3em;
  line-height: .8em;

}

.t_cabezera .factura_numero {
  font-weight: bold;
  font-size: 1.3em;
  line-height: .8em;

}


.text-r {
  text-align: right;
}

/* cliente  */

.box-cliente {
  border: 1px solid black;
  border-radius: 10px
}

.cliente {
  border-collapse: collapse;
}

.cliente .data_2,
.cliente .data_1 {
  /*background-color: yellow;*/
}

.cliente .data_1 .border,
.cliente .data_2 .border {
  /*border: 1px solid black;*/
  padding: 5px 10px;
}


.cliente .cliente_factura,
.cliente .doc_interno {
  display: inline-block;
  border: 1px solid #666666;
  padding: 10px;
  padding-bottom: 5px;
  height: 80px;
  border-radius: 10px;
}

.cliente .cliente_factura {
  width: 70%;
}

.cliente .cliente_tipodato {
  font-weight: bold;
}


.border-radius {
  border: 1px solid black;
  border-radius: 10px;
}

/* info pago */
.info_pago {
  border-collapse: collapse;
}

.data_pago {
  border-right: 1px solid black;
  border-left: 1px solid black;
  padding: 5px;
  padding-bottom: 2px;
  padding-top: 2px;
  margin: 0;
}

.no-border-right {
  border-right: none;
}

.no-border-left {
  border-left: none;
}


.data_pago .cliente_dato {}

.data_pago .cliente_tipodato {
  font-weight: bold;
}

.items {
  position: relative;
  top: -90px;
}

.table_factura {
  /*background-color: #ccc;*/
  text-align: center;
  width: 100%;
  border-radius: 10px;
  border-collapse: collapse;
  /* border: 1px solid black; */
  margin-top: 3px;
  margin-bottom: 3px;
}

.table_factura thead {
  background-color: #ccc;
  color: black;
}

.table_factura thead td {
  border-left: none;
  border-right: none;
  border-bottom: 1px solid #000000;
}

.table_factura tbody tr td {
  border-left: none;
}

.table_factura tbody td {}

.table_factura tbody tr:last-child td {
  /* border-bottom: 1px solid black */
}


.table_factura tbody td {}

.items_data {}

/*pie*/
.row_pie {
  overflow: hidden;
  width: 100%;
  {{-- position: relative; --}}
  position: absolute;
  bottom: 0;
  padding: 0px;
  margin: 0;
}


/*pie*/
.small .row_pie {
  overflow: hidden;
  width: 100%;
  position: relative;
}


.pie .data_1 {}

.pie .data_1 {}

.pie .data_1 p {
  padding: 0;
  padding-left: 10px;
  margin: 0;
}

.pie .data_1 .td-qr {
  padding: 0;
  height: 40px;
  min-height: 40px;
  max-height: 40px;
  margin: 0;
}

.pie .data_1 .td-qr img {
  /* position: absolute; */
  padding: 0;
  margin: 0;
  min-height: 80px;
}


.referencia {
  border: 1px solid black;
  border-radius: 10px;
  margin-bottom: 5px;
}

.referencia-fp {
  border-bottom: 1px solid black;
  border-top: 1px solid black;
  margin-bottom: 5px;
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
  border: 1px solid #333;
  border-radius: 10px
}

.pie .data_1 {
  padding-left: 0px;
}

.pie .data_2 {
  border-left: 1px solid #999;
}

.pie .items_resumen,
.pie .items_totales {
  padding: 0;
  display: inline-block;
}

.pie .items_resumen p {
  margin: 0;
  padding: 0;
}

.lipo {
  /*outline: 1px solid black;*/
}

.info_doc {}

.text-c {
  text-align: center;
}

.table_totales {
  border-collapse: collapse;
}


.table_totales td {
  padding: 0;
}

.table_totales td:first-child {}

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
  margin-top: 10px;
}

.table_totales .nombre_total {
  padding-left: 10px;
}

.title_c {
  margin-bottom: 0px;
  font-weight: 600;
  text-transform: uppercase;
}

#cuentas {}

#cuentas td {
  padding: 0;
}

#cuentas {}

.text-right {
  text-align: right;
}


body {
} 


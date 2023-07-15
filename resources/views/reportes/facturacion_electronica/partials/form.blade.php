<form action="{{ route('reportes.facturacion_electronica.pdf') }}" method="get">
<div class="form-electronica">
<div class="row">  

  <div class="form-group col-md-4">  
    <div class="input-group">
    <span class="input-group-addon">Tipo documento</span>
        <select name="td" class="form-control input-sm keyjump">          
          <option value="">  ---- Seleccionar ----  </option>
          <option value="01"> FACTURA </option>
          <option value="07"> NOTA CREDITO </option>
          <option value="08"> NOTA DEBITO </option>
        </select>
    </div>
  </div>

  @php
    $fechas = fechas_reporte();
  @endphp

 <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Fecha Inicio</span>
      <input name="fecha_emision" data-date-format="yyyy-mm-dd" data-fecha_inicial="{{ $fechas->inicio }}" required="required"  class="form-control input-sm datepicker" value="{{ $fechas->inicio }}" type="text">
    </div>
  </div>

 <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Fecha Fin</span>
      <input name="fecha_final" data-date-format="yyyy-mm-dd" data-fecha_inicial="{{ $fechas->final }}" required="required"  class="form-control input-sm datepicker" value="{{ $fechas->final }}" type="text">
    </div>
  </div>

</div>



<div class="row">  

  <div class="form-group col-md-6">  
    <div class="input-group">
    <span class="input-group-addon">Estado Sistema</span>
        <select name="estado_sistema" class="form-control input-sm keyjump">          
          <option value=""> ---- Seleccionar ---- </option>
          <option value="0"> ENVIADO </option>
          <option value="9"> PENDIENTE </option>
        </select>
    </div>
  </div>

  <div class="form-group col-md-6">  
    <div class="input-group">
    <span class="input-group-addon">Estado Sunat</span>
        <select name="estado_sunat" class="form-control input-sm keyjump">          
          <option value="">  ---- Seleccionar ----  </option>
          <option value="0001"> ACEPTADO </option>
          <option value="0002"> RECHAZADO </option>
          <option value="0003"> DE BAJA </option>
          <option value="0011"> PENDIENTE </option>
        </select>
    </div>
  </div>

</div>


<div class="row">  

  <div class="col-md-6">
    <a href="#" class="search-table btn btn-primary"> <span class="fa fa-search"> </span> Buscar </a>
  </div>

 <div class="form-group col-md-4 pr-0">  
      <select name="tipo_reporte" required class="form-control input-sm">          
        <option value=""> -- Selecciona Tipo de Reporte -- </option>
        <option value="pdf"> PDF </option>
        <option value="xsl"> XSL </option>
      </select>
  </div>


  <div class="form-group col-md-2 pl-0">
    <button class="btn btn-success btn-sm btn-block " type="submit"> <span class="fa fa-cloud"> </span>  Descargar Reporte </button>

    {{-- <div class="input-group">
      <select name="estado_sunat" required class="form-control input-sm">          
        <option value=""> -- Selecciona Tipo de Reporte -- </option>
        <option value="PDF"> PDF </option>
        <option value="XSL"> XSL </option>
      </select>

      <span class="input-group-addon padding-none border-none">
        <button class="btn btn-success btn-sm " type="submit"> <span class="fa fa-cloud"> </span>  Descargar Reporte </button>
      </span>
    </div> --}}

  </div>

</div>




</div>
</form>
<form action="{{ route('empresa.update_parametros_basic' , $empresa->id()) }}" method="post">
  {{ csrf_field() }}
  
  @method('PUT')

  <div class="row">	

    <div class="form-group col-md-3 ">  
      <label> Tipo de impresión</label>
      
      <select class="form-control input-sm" name="formato_hoja" type="text" value="">     
        <option {{ $empresa->isA4() ? 'selected=selected' : ''  }} value="0"> A4 </option>
        <option {{ $empresa->isA5() ? 'selected=selected' : ''  }} value="1"> A5 </option>
        <option {{ $empresa->isTicket() ? 'selected=selected' : ''  }} value="2"> Ticket </option>
      </select>
    </div>

    <div class="form-group col-md-3 ">  
      <label> Precio incluye IGV </label>
      <select class="form-control input-sm" name="PrecIIGV" type="text" value="">     
        <option {{ $empresa->incluyeIgv()  ? 'selected=selected' : ''  }} value="1"> Si </option>
        <option {{ !$empresa->incluyeIgv() ? 'selected=selected' : ''  }} value="0"> No </option>
      </select>
    </div>

  </div>


  <div class="botones row">
    <!-- left -->
    <div class="col-md-12 col-lg-12 col-sm-12 no_pr">
      <button class="btn btn-primary btn-flat" id="guardarFactura"> <span class="fa fa-save"> </span> Guardar </button>
      <a href="{{ route('home') }}" class="btn btn-danger btn-flat" id="salir"> <span class="fa fa-power-off"> </span> Salir </a>
    </div>
  </div>


</form>
<div class="row" style="border-left:3px solid #dfdfdf">

  @if( $isCreate )
  <div class="col-md-12 form-group">
    <label for="form-telefono"> Usar lista de precios de: </label>
    <select name="lista_copy_id" required class="form-control text-uppercase">
      <option value=""> -- Elegir Lista De Precios -- </option>
      @foreach($listas as $lista)
      <option data-nombre="{{ $lista->LisNomb }}" value="{{ $lista->LisCodi }}"> {{ $lista->LisNomb }} - Local {{ $lista->local->LocNomb }}
      </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-12 form-group">
    <label for="form-telefono"> Nombre: </label>
    <input type="text" name="lista_nombre" placeholder="L.Pub" required class="form-control text-uppercase">
  </div>


  @else

    <div class="col-md-12 form-group">
      <label for="form-telefono"> Listas de Precios </label>
        @foreach($listas as $lista)
          <div class="input-group no-border input-readonly">
            <input  class="form-control" readonly value="{{ $lista->LisNomb }}" />
            <span title="Modificar" data-toggle="tooltip" class="input-group-btn"> <a target="_blank" href="{{ route('listaprecio.edit', $lista->LisCodi ) }}" class="btn btn-default btn-flat"> <span class="fa fa-pencil"></span> </a> </span>

          </div>
        @endforeach
    </div>

  @endif


</div>




<div class="row" style="margin-top:2em"  style="border-left:3px solid #dfdfdf">

  <div class="col-md-12 form-group">
    <label for="form-telefono"> Mostrar Todas las direcciones de locales si existen:</label>
    <select name="PDFLocalNombreInd" required class="form-control text-uppercase">
      <option value="1" {{ $model->PDFLocalNombreInd == "1"  ? 'selected' : '' }}> Si </option>
      <option value="0" {{ $model->PDFLocalNombreInd == "0"  ? 'selected' : '' }}> No </option>
    </select>
  </div>


</div>
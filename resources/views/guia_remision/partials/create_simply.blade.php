<!-- <div class="row"> -->
<!-- <div class="col-md-12"> -->

<form id="formCg" method="post" action="{{ route('guia.store.simply',  $venta->VtaOper ) }}">
  @method('post')
  @csrf

  <div class="row">

    <div class="col-md-3">
      <div class="form-group">
        <label> Documento </label>
        <input type="text" class="form-control" disabled value="{{ $venta->VtaNume }}">
      </div>
    </div>

    <div class="col-md-2">
      <div class="form-group">
        <label for="tipo_guia"> Tipo Guia </label>
        <select id="tipo_guia" class="form-control" name="tipo" value="{{ $venta->DocRefe }}">
          <option value="1">Interna</option>
          <option value="2">Electroncia</option>
        </select>
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        <label for="almacen_id_2"> Almacen </label>
        <select id="almacen_id_2" name="almacen_id" class="form-control">
          @foreach( $almacenes as $almacen )
          <option value="{{ $almacen->loccodi }}" {{ $almacen->loccodi == auth()->user()->local() ? 'selected' : '' }}> {{ $almacen->local->LocNomb }} </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-md-4">
      <div class="form-group">
        <label for="id_fe"> Fecha emisión </label>
        <input id="id_fe" type="text" name="fecha_emision" class="form-control " id="fecha_emision" value="{{ date('Y-m-d')  }}">
      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="obs_fe"> Observación </label>
        <input id="obs_fe" type="text" class="form-control" name="observacion" value="">
      </div>
    </div>
  </div>


  <table class="table sainfo-table table-sm">
    <thead>
      <tr>
        <td> Codigo </td>
        <td> Producto </td>
        <td> Total </td>
        <td> Enviado </td>
        <td> Por Enviar </td>
        <td> </td>
      </tr>
    </thead>
    <tbody>
      @foreach( $items as $item )
      <tr>
        <td> {{ $item->DetCodi }} <input type="hidden" name="items[{{ $loop->index  }}][id]" value="{{ $item->Linea }}"> </td>
        <td> {{ $item->DetNomb }} </td>
        <td> {{ $item->DetCant }} </td>
        <td> {{ $item->porEnviar() }} </td>

        <td>
          <input type="number" min="0" max="{{ $item->DetSdCa }}" step="any" class="input-cantidad" name="items[{{ $loop->index  }}][cantidad]" value="{{ $item->DetSdCa }}">
        </td>

        <td>
          <a href="#" class="remove-item btn btn-xs btn-flat btn-default" style="color:red"> <span class="fa fa-minus"></span> </a>
        </td>

      </tr>
      @endforeach
    </tbody>
  </table>

  <button class="btn btn-primary btn-flat"> <span class="fa fa-save"> </span> Guardar </button>
</form>
<!-- </div> -->
<!-- </div> -->
@php
$column_mov = $column_mov ?? false;
$cant_locales = 8;
@endphp



<div class="modal modal-seleccion fade" id="modalSelectProducto">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">Buscar Productos </h4>
      </div>
      <div class="modal-body">

        <div class="botones_div">
          <div class="row">
            <div class="col-md-2  overflow-hidden">
              <a class="btn pull-left btn-success btn-flat elegir_elemento"> <span class="fa fa-check"> </span> Aceptar</a>
            </div>

            <!-- -- -->
            <div class="form-group col-md-4 p-0">
              <div class="form-group">

                <select name="grupo_filter" data-url="{{ route('productos.buscar_grupo') }}" required="required" class="form-control">
                  <option data-familias="" value=""> -- SELECCIONAR GRUPO -- </option>
                  @foreach( $grupos as $grupo )
                  <option data-familias="{{ $loop->first ? $grupo->familias() : '' }}" value="{{ $grupo->GruCodi }}">{{ $grupo->GruNomb }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group col-md-4">
              <div class="form-group">
                <select name="familia_filter" required="required" class="form-control">
                  <option data-familias="" value=""> -- SELECCIONAR FAMILIA -- </option>
                </select>
              </div>
            </div>
            <!-- -- -->

            <div class="col-md-2">
              <a target="_blank" class="btn pull-right btn-default btn-flat" href="{{ route('productos.index' ) }}"> <span class="fa fa-plus"> </span> Nuevo </a>
            </div>

          </div>
        </div>

        <div class="productos_select">

          <table width="80%" class="table table-oneline table-bordered sainfo-table" id="datatable-productos">
            <thead>
              <tr>
                <td> Código </td>
                <td> Unidad </td>
                <td width="200px" class="nombre_prod"> Nombre </td>
                <td> Marca </td>
                <td> Costo ($)</td>
                <td> Costo (S)</td>
                <td> Margen</td>
                <td> Prec. Vta </td>
                <td> Stock Tot </td>
                @if( isset($locales) )
                @php
                  $locales = $locales->toArray();
                @endphp

                @for( $i = 0; $i < 8; $i++) 
                
                @php 
                  if( isset($locales[$i]) ){
                    $local_id = substr( $locales[$i]['local']['LocCodi'] , -1);
                    $local_name = $locales[$i]['local']['LocNomb']; 
                  } 
                  else {
                    continue;
                  }
                @endphp 
                
                <td class="almacenes" data-id="{{ $local_id }}"> <span class="td-almacen-title"> Almacen </span> {{ $local_name }} </td>

                @endfor

                  @else
                  <td class="almacenes" data-id="1"> <span class="td-almacen-title"> Almacen </span> 1 </td>
                  <td class="almacenes" data-id="2"> <span class="td-almacen-title"> Almacen </span> 2 </td>
                  <td class="almacenes" data-id="3"> <span class="td-almacen-title"> Almacen </span> 3 </td>
                  <td class="almacenes" data-id="4"> <span class="td-almacen-title"> Almacen </span> 4 </td>
                  <td class="almacenes" data-id="5"> <span class="td-almacen-title"> Almacen </span> 5 </td>
                  <td class="almacenes" data-id="6"> <span class="td-almacen-title"> Almacen </span> 6 </td>
                  <td class="almacenes" data-id="7"> <span class="td-almacen-title"> Almacen </span> 7 </td>
                  <td class="almacenes" data-id="8"> <span class="td-almacen-title"> Almacen </span> 8 </td>
                  @endif

                  <td> <span class="td-almacen-title"> Stock </span> Reserva  </td>
                  
                  <td> Peso </td>
                  <td> Base IGV </td>
                  <td> ISC </td>
                  <td> TieCodi </td>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

        </div>
      </div>

    </div>


  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
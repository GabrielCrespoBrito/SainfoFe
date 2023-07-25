{{-- @dd($locales) --}}

@php
$indexColumns = 0;
$column_mov = $column_mov ?? false;
$cant_locales = 8;
$ver_costos = $ver_costos ?? true;
@endphp

<div class="modal modal-seleccion fade" id="modalSelectProducto">
  <div class="modal-dialog modal-lgg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Buscar Productos </h4>
      </div>
      <div class="modal-body">
        <div class="botones_div">
          <div class="row">
            <div class="col-md-2 form-group overflow-hidden">
              <a class="btn pull-left btn-block btn-success btn-flat elegir_elemento"> 
              <span class="fa fa-check"> </span> Aceptar</a>
            </div>
            <!-- -- -->
            <div class="col-md-4">
              <div class="form-group">
                <select name="grupo_filter" data-url="{{ route('productos.buscar_grupo') }}" required="required" class="form-control">
                  <option data-familias="" value=""> -- SELECCIONAR GRUPO -- </option>
                  @foreach( $grupos as $grupo )
                  <option data-familias="{{ $loop->first ? $grupo->familias() : '' }}" value="{{ $grupo->GruCodi }}">{{ $grupo->GruNomb }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <select name="familia_filter" required="required" class="form-control">
                  <option data-familias="" value=""> -- SELECCIONAR FAMILIA -- </option>
                </select>
              </div>
            </div>
            <div class="col-md-2  hidden-sm  hidden-xs">
              <a target="_blank" class="btn pull-right btn-default btn-flat" href="{{ route('productos.index' ) }}"> <span class="fa fa-plus"> </span> Nuevo </a>
            </div>

          </div>
        </div>

        <div class="productos_select">
          <table data-costos="{{ (int) $ver_costos  }}" width="100%" class="table table-oneline table-bordered sainfo-table" id="datatable-productos">
            <thead>
              <tr>
                <td data-column="{{ $indexColumns }}"> Código </td>
                <td data-column="{{ ++$indexColumns  }}"> Unidad </td>
                <td data-column="{{ ++$indexColumns }}" width="200px" class="nombre_prod"> Nombre </td>
                @if($column_mov)
                <td data-column="{{ ++$indexColumns }}"> Mov </td>
                @endif
                <td data-column="{{ ++$indexColumns  }}"> Marca </td>
                @if($ver_costos)
                <td data-column="{{ ++$indexColumns }}"> Costo ($)</td>
                <td data-column="{{ ++$indexColumns }}"> Costo (S)</td>
                <td data-column="{{ ++$indexColumns }}"> Margen</td>
                @endif
                <td data-column="{{ ++$indexColumns }} "> Prec. Vta </td>
                <td data-column="{{ ++$indexColumns }}" class="show-total almacen-showhide" data-id="total" data-hide="1" data-main="0"> Stock Tot </td>
                @if( isset($locales) )
                @php
                ++$indexColumns;
                $locales = $locales->toArray();
                @endphp
                @for( $i = 0; $i < 8; $i++, $indexColumns++) 
                
                @php 
                  if( isset($locales[$i]) ){ 
                    $local_id = substr($locales[$i]['local']['LocCodi'],-1);
                    $local_main = $locales[$i]['defecto'];
                    $local_name= $locales[$i]['local']['LocNomb']; 
                    } else { continue; } 
                @endphp
                  
                  <td data-column={{ $indexColumns }} class="almacenes {{ $local_main ? "" : "almacen-showhide" }} " data-hide="0"  data-main="{{ $local_main }}" data-id="{{ $local_id }}"> <span class="td-almacen-title"> Almacen </span> {{ $local_name }}
                    
                    @if($local_main)
                    <a 
                      data-hide="0"
                      title="Ver Todos Los Almacenes" 
                      href="#" 
                      data-columns=""
                      class="btn btn-flat btn-default btn-xs ver-mostrar-almacenes"> <span class="fa fa-eye"></span> 
                    </a>
                    @endif
                  </td>
                
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
                  {{-- <td> Properc </td> --}}
                  <td data-column={{ ++$indexColumns }}> <span class="td-almacen-title"> Stock </span> Reserva  </td>
                  <td data-column={{ ++$indexColumns }}> Peso </td>
                  <td data-column={{ ++$indexColumns }}> Base IGV </td>
                  <td data-column={{ ++$indexColumns }}> ISC </td>
                  <td data-column={{ ++$indexColumns }}> TieCodi </td>
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
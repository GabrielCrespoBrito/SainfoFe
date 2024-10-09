@php
  $default_unit = $default_unit ?? "NIU";
@endphp

   <div class="row">
        <div class="form-group col-md-6">
          <label> Grupo </label>                    
          <div class="input-group">
            <select data-namedb="" name="grupo" required="required" class="form-control">
              @foreach( $grupos as $grupo )
                <option data-familias="{{ $loop->first ? $grupo->familias() : '' }}" value="{{ $grupo->GruCodi }}">{{ $grupo->GruNomb }}</option>
              @endforeach 
            </select>
            <div class="input-group-addon">
              <a target="_blank" href="{{ route('grupos.index', ['create' => 1 ]) }}" class=""> <i class="fa fa-plus"></i> </a>              
            </div>            
          </div>
        </div>

        <div class="form-group col-md-6">
          <label> Familia</label>
          <div class="input-group">          
          <select name="familia"  required="required" class="form-control">
            @foreach( $grupos->first()->familias() as $familia )            
            <option value="{{ $familia->famCodi }}">{{ $familia->famNomb }}</option>
            @endforeach 
          </select>
           <div class="input-group-addon">
              <a target="_blank" href="{{ route('familias.index', ['create' => 1 ]) }}" class=""> <i class="fa fa-plus"></i> </a>              
            </div>             
          </div>
        </div>

      </div>

      <div class="row">

        <div class="form-group col-md-6">
          <label> Marca</label>
          <div class="input-group">                    
          <select name="marca"  required="required" class="form-control">
            @foreach( $marcas as $marca )
            <option value="{{ $marca->MarCodi }}">{{ $marca->MarNomb }}</option>
            @endforeach             
          </select>
           <div class="input-group-addon">
              <a target="_blank" href="{{ route('marcas.index', ['create' => 1 ]) }}" class=""> <i class="fa fa-plus"></i> </a>              
            </div>             
          </div>          
        </div>

        <div class="form-group col-md-6">
          <label> Procedencia</label>
          <div class="input-group">                              
          <select name="procedencia"  required="required" class="form-control">
            @foreach( $procedencias as $procedencia )
            <option value="{{ $procedencia->ProcCodi }}">{{ $procedencia->ProcNomb }}</option>
            @endforeach               
          </select>
           <div class="input-group-addon">
              <a href="#" class=""> <i class="fa fa-plus"></i> </a>              
            </div>             
          </div>          
        </div>
                
      </div>

      <div class="row">

        <div class="form-group col-md-4">
          <label> N° operación</label>
          <input name="codigo" readonly="readonly" required="required" class="form-control text-uppercase"/>    
        </div>

        <div class="form-group col-md-4">
          <label> Codigo </label>
          <input name="numero_operacion" required="required" class="form-control text-uppercase" />
        </div>

        <div class="form-group col-md-4">
          <label> Codigo de barra</label>
          <input name="codigo_barra" required="required" class="form-control text-uppercase" />
        </div>
                       
      </div>

      <div class="row">

        <div class="form-group col-md-12">
          <label> Nombre</label>
          <input name="nombre" required="required" class="form-control text-uppercase">
        </div>

      </div>


      <div class="row">

        <div class="form-group col-md-8">
          <label> Tipo existencia</label>
            <select name="tipo_existencia" class="form-control">
            @foreach( $tipos_existencias as $tipo_existencia )
            <option value="{{ $tipo_existencia->TieCodi }}"> {{ $tipo_existencia->TieNomb }}
            </option>
            @endforeach               
          </select>          
        </div>

        <div class="form-group col-md-4">
          <label> Moneda</label>
          <select name="moneda"  required="required" class="form-control">
            @foreach( $monedas as $moneda )
            <option value="{{ $moneda->moncodi }}">{{ $moneda->monnomb }}</option>
            @endforeach               
          </select>
        </div>

      </div>
    


      <div class="row">

        <div class="form-group col-md-3">
          <label> Unidad</label>
          <select data-default="{{ $default_unit }}" name="unidad" required="required" class="form-control">
            @foreach( $unidades as $unidad )
            <option  {{ $default_unit == $unidad->UnPCodi ? 'selected' : ''  }} value="{{ $unidad->UnPCodi }}">{{ $unidad->UnPNomb }}</option>

            @endforeach             
          </select>
        </div>

        <div class="form-group col-md-3">
          <label> Base IGV</label>
          <select name="base_igv"  required="required" class="form-control">
            <option data-value="18" value="GRAVADA"> GRAVADA</option>
            <option data-value="0" value="INAFECTA"> INAFECTA</option>
            <option data-value="0" value="EXONERADA">EXONERADA</option>
            <option data-value="0" value="GRATUITA"> GRATUITA</option>            
          </select>
        </div>

        <div class="form-group col-md-6">
          <label> Codigo Sunat </label>
        <select name="profoto2" data-settings={{ json_encode(['allowClear' => 'true' ])  }} data-url="{{ route('sunat.productos') }}" id="cod_sunat" data-id="" data-text=""  class="form-control select2">
          </select>
        </div>

        {{-- <div class="form-group col-md-4">
          <label>%</label>
          <input name="igv_porc" min="0" type="number" data-default="18" value="18" class="form-control">
        </div> --}}

      </div>


     <div class="row">

        <div class="form-group col-md-3">
          <label> Costo </label>
          <input name="costo" data-default="0" required="required" class="form-control" type="text">
        </div>
        
        
        <div class="form-group col-md-3">
          <label> Utilidad (%)  </label>
          <input name="utilidad" data-default="0" required="required" class="form-control" type="text">
        </div>

        <div class="form-group col-md-3">
          <label> Precio de venta  </label>
          <input name="precio_venta" data-default="0" required="required" class="form-control" type="text">
        </div>
        
        <div class="form-group col-md-3">
          <label> Precio Min. Venta  <span data-toggle="tooltip" title="Si se establece un valor mayor a 0, ese sera el precio minimo al que se podra vender este" class="fa fa-info-circle"></span> </label>
          <input name="precio_min_venta" data-default="0" required="required" class="form-control" type="text">
        </div>

        {{-- <div class="form-group col-md-1 div-unidad-link">
          <label> <span class="fa fa-edit"> <span> </label>
          <a data-href="{{ route('unidad.index', 'XX' ) }}" href="" class="btn btn-sm btn-primary unidad-link" data-toggle="tooltip" target="_blank" title="Manejar Precios"> <span class="fa fa-edit"></span> </a>
        </div>  --}}

      </div>      


     <div class="row">

        <div class="form-group col-md-3">
          <label> Peso </label>
          <input name="peso" data-default="0" value="0" required="required" class="form-control" type="text">
        </div>

        <div class="form-group col-md-2">
          <label> ISC </label>
          <input name="isc" data-default="0" value="0" required="required" class="form-control" type="text">
        </div>        
                

        <div class="form-group col-md-2">
          <label> Ubicación </label>
          <input name="ubicacion" required="required" class="form-control" type="text">
        </div>
        
        <div class="form-group col-md-2">
          <label title="Porcentaje de comisión del vendedor"> % Comc. Vend </label>
          <input name="porc_com_vend" data-default="0" value="0" required="required" class="form-control" type="text">
        </div>       

        {{--
        <div class="form-group col-md-3">
          <label> Stock minimo (%) </label>
          <input name="stock_minimo" data-default="0" value="0" required="required" class="form-control" type="text">
        </div>
        --}}

        <div class="form-group col-md-3">
          
          <label for="ProSTem">  Manejo Stock
            <input type="checkbox" value="1" id="ProSTem" name="ProSTem" data-default="1"  />  
          </label>

          <div class="input-group">
          <input min="0" name="stock_minimo" required data-default="0" value="0" required="required" class="form-control" type="number">
          <span class="input-group-addon">Stock. Min</span>
          </div>
        </div> 


      </div>  


     <div class="row">

      <div class="checkbox col-md-12">

        <label>
          <input name="ProPerc" data-default="1" value="1" type="checkbox"> Afecto Percepción
        </label>
        
        <label style="margin-left:20px">
          <input name="icbper" data-default="1" value="1" type="checkbox"> Afectación al ICBPER (Bolsas plásticas)
        </label>
        
        <label style="margin-left:20px">
          <input name="incluye_igv" data-default="1" value="1" checked="checked" type="checkbox"> Incluye IGV
        </label>

        <label style="margin-left:20px">
          <input name="estado" data-default="1" value="1" checked="checked" type="checkbox"> Estado
        </label>

        {{-- <label style="margin-left:20px">
          <input name="ProSTem" data-default="1" value="1" checked="checked" type="checkbox"> Manejo Stock
        </label> --}}

      </div>                
     
      </div>    
    
    <br>

     <div class="row">
      <div class="checkbox col-md-12">
          <input name="imagen" type="file"> Imagen
      </div>            
    </div> 

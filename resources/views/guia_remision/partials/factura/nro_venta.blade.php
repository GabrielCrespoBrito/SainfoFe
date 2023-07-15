 @php
 
 @endphp

{{-- @dd( "locales",  $locales ) --}}


<div class="row">  

  <div class="form-group  {{ $isIngreso ? 'col-md-2' : 'col-md-4' }} no_pr">  
    <div class="input-group">
      <span class="input-group-addon">Nro Oper</span>
        <input class="form-control input-sm" name="nro_oper" type="text" readonly="readonly" value="{{ $guia->GuiOper }}">     
    </div>
  </div>

  {{-- <div class="form-group  {{ $isIngreso ? 'col-md-2' : 'col-md-4' }} no_pr">  
    <div class="input-group">
      <span class="input-group-addon">Almacen</span>
      <select {{ $show ? 'disabled' : ''  }} name="id_almacen" class="form-control input-sm">
        @foreach( $almacenes as $almacen )                    
          <option value="{{ $almacen->LocCodi }}"  {{ $almacen->LocCodi == auth()->user()->local()  ? 'selected' : ''  }}>{{ $almacen->LocNomb }}</option>
        @endforeach
      </select>
    </div>
  </div> --}}

  <div class="form-group  {{ $isIngreso ? 'col-md-2' : 'col-md-4' }} no_pr">  
    <div class="input-group">
      <span class="input-group-addon">Almacen</span>
      <select {{ $show ? 'disabled' : ''  }} name="id_almacen" class="form-control input-sm">
        @foreach( $locales as $almacen )               
          <option value="{{ $almacen->local->LocCodi }}"  {{ $almacen->local->LocCodi == auth()->user()->local()  ? 'selected' : ''  }}>{{ $almacen->local->LocNomb }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="form-group {{ $isIngreso ? 'col-md-2' : 'col-md-4' }}">  
    <div class="input-group">
      <span class="input-group-addon">Tipo Mov.</span>
        <select name="id_tipo_movimiento" class="form-control input-sm">
          @foreach( $tipos_movimientos as $tipo_movimiento )
            <option value="{{ $tipo_movimiento->Tmocodi }}" {{ $guia->TmoCodi == $tipo_movimiento->Tmocodi ? 'selected' : ''  }}> {{ $tipo_movimiento->TmoNomb }} </option>
          @endforeach 
        </select>
    </div>
  </div>

  @if( $isIngreso )
  
    <div class="form-group col-md-2 no_pr">  
      <div class="input-group">
        <span class="input-group-addon">Nro Guia</span>
          <input class="form-control guia-ids input-sm text-uppercase" name="GuiSeri" maxlength="4" type="text" value="{{ $guia->GuiSeri }}">     
      </div>
    </div>

    <div class="form-group col-md-4 no_pr">  
      <div class="input-group">
        <span class="input-group-addon"></span>
          <input class="form-control guia-ids input-sm" name="GuiNumee" maxlength="6" type="text" value="{{ $guia->GuiNumee }}">

      </div>
    </div>
  
  @endif

</div>
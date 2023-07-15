<!-- Banco -->
<div class="banco">
  <div class="title"> Banco </div>
    @php 
      if(!isset($bancos)){
        $empresa = get_empresa();
        $bancos = $bancos ?? $empresa->bancos;
      }
      $numDocField = $isVenta ? 'VtaNume' : 'CpaNume';
    @endphp
  <div class="row">
    <div class="form-group col-md-12">  
      <div class="input-group">
        <span class="input-group-addon">Cuenta</span>
        <select class="form-control input-sm" data-db="CuenCodi" name="CuenCodi">
          @foreach( $bancos as $cuenta )
            @if( $cuenta->isAperturada() )
              <option {{ $loop->first ? 'selected=selected' : '' }} value="{{ $cuenta->CueCodi }}"> {{ $cuenta->banco->bannomb }} | {{ $cuenta->getMonedaAbreviatura() }} {{ $cuenta->CueNume }}</option> 
            @endif
          @endforeach
        </select>                   
      </div>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">  
      <div class="input-group">
        
        <span class="input-group-addon">Nro Oper:</span>

        <input class="form-control input-sm disabledFijo"  data-db="NumOper" name="NumOper" disabled="disabled" value="">      

      </div>
    </div>
    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon">Nro Doc/Váucher:</span>
        <input class="form-control input-sm" data-db="NumDoc" data-field="{{ $numDocField }}" name="NumDoc" value="">     
      </div>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon">Fecha pago:</span>
        <input class="form-control input-sm datepicker" data-db="fechaPago" data-format="yyyy-mm-dd"  name="fechaPago" value="{{ date('Y-m-d') }}">      
      </div>
    </div>

    <div class="form-group col-md-6">  
      <div class="input-group">
        <span class="input-group-addon">Fecha Ven:</span>
        <input class="form-control input-sm datepicker" data-format="yyyy-mm-dd" name="fechaVen" value="{{ date('Y-m-d') }}">      
      </div>
    </div>
  </div>

</div>
<!-- /banco -->
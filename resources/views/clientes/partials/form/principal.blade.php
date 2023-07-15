@php
  $ruc = $ruc ?? "";
  $razon_social = $razon_social ?? "";
  $telf = $telf ?? "";
  $email = $email ?? "";
  $tipoDocumentoDefault = $tipoDocumentoDefault ?? '';
  $disabledBtnSearch = $disabledBtnSearch ?? true;
@endphp
    


<div class="row">

  <div class="form-group col-md-4">
    <label> Código * </label>
    <input readonly="readonly" name="codigo" required="required" class="form-control" value="" type="text">
  </div>

  <div class="form-group col-md-8">
    <label> Tipo *</label>
      @php
        $tipos_clientes = isset($tipos_clientes) ? $tipos_clientes : App\TipoCliente::all();
      @endphp
    <select name="tipo_cliente"  required="required" class="form-control">
      @foreach( $tipos_clientes as $tipo )
        @php
          $tipoEntidadSelected = $defaultEntity == $tipo->TippCodi;
        @endphp
        <option {{ $tipoEntidadSelected ? 'selected=selected' : ''  }} {{ $closedTipo ? ($tipoEntidadSelected ? '' : 'disabled'  ) : ''   }}  value="{{ $tipo->TippCodi }}"> {{ $tipo->TippNomb }}</option>
      @endforeach
    </select>
  </div>
</div>

@section('name')
    
@endsection<div class="row">
  <div class="form-group col-md-5">
    <label> Tipo Doc*</label>
    @php
      $tipos_documentos_clientes = isset($tipos_documentos_clientes) ? $tipos_documentos_clientes : App\TipoDocumento::all();
    @endphp
    <select name="tipo_documento" data-cantidad_digitos="" required="required" class="form-control">
      @foreach( $tipos_documentos_clientes as $tipo )
        <option {{ $tipoDocumentoDefault == $tipo->TDocCodi ? 'selected' : ''  }}  value="{{ $tipo->TDocCodi }}"> {{ $tipo->TdocNomb }}</option>
      @endforeach            
    </select>
  </div>

  <div class="form-group col-md-5">
    <label> N° Documento </label>

    <input name="ruc" readonly="readonly" required="required" min="0" class="form-control" value="{{ $ruc  }}" type="number">
  </div>

  <div class="form-group col-md-2">
    <label> - </label>
    <button data-toggle="tooltip" {{ $disabledBtnSearch ? "disabled='disabled'" : '' }} title="Consultar Sunat" class="btn btn-default form-control verificar_ruc" type="button"> <img src="{{ asset('images/sunat_icono_peque.png') }}">  </button>
    <!-- <input class="form-control" value="" type="text"> -->
  </div>


</div>


<div class="row">

  <div class="form-group col-md-12">
    <label> Razon social </label>
  <input name="razon_social" required="required" class="form-control text-uppercase" value="{{ $razon_social }}" type="text">
  </div>  

  <div class="form-group col-md-12">
    <label> Dirección Fiscal: </label>
    <input name="direccion_fiscal"  required="required" class="form-control text-uppercase" type="text">
  </div>  

</div>


<div class="row">

  <div class="form-group col-md-12">
    <label> Ubigeo </label>
    @include('components.select2', ['id' => 'ubigeo' , 'data_id' => '', 'url' => route('clientes.ubigeosearch') , 'name' => 'ubigeo' , 'size' => '' ])
  </div>

</div>  

<div class="row">

  <div class="form-group col-md-6">
    <label> Telefono </label>
  <input name="telefono_1" required="required" class="form-control" value="{{ $telf }}" type="text">
  </div>
  
  <div class="form-group col-md-6">
    <label> Email  </label>
    <input name="email" required="required" class="form-control" type="email" value="{{ $telf }}" >
  </div>
  
</div>  

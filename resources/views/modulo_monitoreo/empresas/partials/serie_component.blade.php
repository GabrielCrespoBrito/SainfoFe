@php
 $tipoSelected = $serie->tipo_documento ?? $tipoSelected ?? null;
 $serieSelected = $serie->serie ?? $serieSelected ?? '';

@endphp

<div class="row parent-serie {{ $isPrincipal ? 'serie-principal' : '' }}">
  
  <input type="hidden" name="series_id[]" value="{{ $serie->id  }}">
  
  <div class="form-group col-md-6">  
    <div class="input-group">
      <span class="input-group-addon"> Tipo documento </span>
      <select name="tipo_documento[]" id="" class="form-control">
        @foreach( $tipos_documentos as $tipo_documento )
          <option {{ $tipo_documento->TidCodi === $tipoSelected ? 'selected=selected' : '' }} value="{{ $tipo_documento->TidCodi }}"> {{ $tipo_documento->TidNomb }} </option>
        @endforeach
      </select>        
    </div>
  </div>

  <div class="form-group col-md-5">
    <div class="input-group">
      <span class="input-group-addon"> Serie </span>
    <input required="required" class="form-control input-sm" style="text-transform:uppercase" minlength="4" maxlength="4"   name="serie[]" type="text" value="{{ $serieSelected }}">
    </div>
  </div>

  <div class="form-group col-md-1">        
    @if($isPrincipal)
      <a href="#" class="btn btn-primary btn-flat agregate-serie"> <span class="fa fa-plus"></span></a>
    @else
      <a href="#" class="btn btn-danger btn-flat remove-serie"> <span class="fa fa-trash"></span></a>
    @endif
  </div>

</div>
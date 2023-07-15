@extends('layouts.master')

@section('bread')
<li>  {{ $empresa->nombre() }} </li>
<li>  Subir Documentos </li>
@endsection

@section('css')
@endsection

@section('js')

  @include('partials.errores')
@endsection

@section('titulo_pagina' , 'Documentos')

@section('contenido')

<div class="row">

  <div class="col-md-6">

    <div class="title"> Resultado </div>

    <form action="#{{route('empresa.storeCertificado', $empresa->empcodi  ) }}" method="post" enctype="multipart/form-data">

      @csrf

      <div class="row">  

        <div class="form-group col-md-12">  
          <div class="input-group">
            <span class="input-group-addon"> Subidos </span>
              <input class="form-control input-sm" readonly="readonly" name="cert_key" type="text" value="">  
          </div>
        </div>

        <div class="form-group col-md-12">  
          <div class="input-group">
            <span class="input-group-addon">.cer</span>
              <input class="form-control input-sm" readonly="readonly" name="cert_cer" type="text" value="">     
          </div>
        </div>


      </div>

      <div class="acciones-div">
        <button type="submit" class="btn btn-block btn-primary btn-flat"> <span class="fa fa-cloud"></span> Guardar </button>
      </div>

    </form>

  </div>



  <div class="col-md-6">

<div class="title"> Documentos respaldados </div>

<form action="#{{route('empresa.storeCertificado', $empresa->empcodi  ) }}" method="post" enctype="multipart/form-data">

  @csrf

  <div class="row">  

    <div class="form-group col-md-12">  
      <div class="input-group">
        <span class="input-group-addon"> Total </span>
          <input class="form-control input-sm" readonly="readonly" name="cert_key" type="text" value="">  
      </div>
    </div>

    <div class="form-group col-md-12">  
      <div class="input-group">
        <span class="input-group-addon"> Con archivos faltantes </span>
          <input class="form-control input-sm" readonly="readonly" name="cert_cer" type="text" value="">     
      </div>
    </div>

    <div style="visibility:hidden" class="form-group {{ $errors->has('cert_pfx') ? 'has-error' : '' }} col-md-12">  
      <div class="input-group">
        <span class="input-group-addon">.pfx</span>
          <input class="form-control input-sm" name="cert_pfx" type="text" value="">     
      </div>
    </div>
    
  </div>

</form>

</div>


</div>

@endsection
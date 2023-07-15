@extends('layouts.master')

@section('bread')
<li> {{ $empresa->nombre() }} </li>
<li>  Subir certificado </li>
@endsection

@section('css')
@endsection

@section('js')

  @include('partials.errores')
@endsection

@section('titulo_pagina' , 'Certificado')

@section('contenido')

<div class="row">

  <div class="col-md-6">

    <div class="title"> Subir certificados </div>

    <form action="{{route('empresa.storeCertificado', $empresa->empcodi  ) }}" method="post" enctype="multipart/form-data">

      @csrf

      <div class="row">  

        <div class="form-group {{ $errors->has('cert_key') ? 'has-error' : '' }} col-md-12">  
          <div class="input-group">
            <span class="input-group-addon">.key </span>
              <input class="form-control input-sm" name="cert_key" type="file" value="">  
          </div>
          <span class="help-block">{{ $errors->first('cert_key') }}</span>
        </div>

        <div class="form-group {{ $errors->has('cert_cer') ? 'has-error' : '' }} col-md-12">  
          <div class="input-group">
            <span class="input-group-addon">.cer</span>
              <input class="form-control input-sm" name="cert_cer" type="file" value="">     
          </div>
          <span class="help-block">{{ $errors->first('cert_cer') }}</span>          
        </div>

        <div class="form-group {{ $errors->has('cert_pfx') ? 'has-error' : '' }} col-md-12">  
          <div class="input-group">
            <span class="input-group-addon">.pfx</span>
              <input class="form-control input-sm" name="cert_pfx" type="file" value="">     
          </div>
          <span class="help-block">{{ $errors->first('cert_pfx') }}</span>
        </div>

      </div>

      <div class="acciones-div">
        <button type="submit" class="btn btn-block btn-primary btn-flat"> <span class="fa fa-save"></span> Guardar </button>
      </div>

    </form>

  </div>

  <div class="col-md-6">

  <div class="title"> Verificar certificados </div>

  @php
    $data = session('checkCertificado');    
    $cer = '';
    $key = '';
    $pfx = '';
    $find = "Se encontro este certificado";
    $notFind = "No se encontro";
    if($data){      
      $cer = $data["key"]['exists'] ? $find : $notFind;
      $key = $data["cer"]['exists'] ? $find : $notFind; 
      $pfx = $data["pfx"]['exists'] ? $find : $notFind;
    }
  @endphp

  <form action="{{route('empresa.checkCertificados', $empresa->empcodi  ) }}" method="post">
    @csrf
        <div class="form-group col-md-12 {{ $data ? return_str( $data['cer'] , true , 'has-success' , 'has-error' ) : '' }}">  
          <div class="input-group">
            <span class="input-group-addon">.key </span>
              <input class="form-control input-sm" readonly="readonly" type="text" value="{{ $cer }}">
          </div>
        </div>


        <div class="form-group  col-md-12 {{ $data ? return_str( $data['key'] , true , 'has-success' , 'has-error' ) : '' }}">  
          <div class="input-group">
            <span class="input-group-addon">.cer</span>
              <input class="form-control input-sm" readonly="readonly" name="cert_cer" type="text" value="{{ $cer }}">     
          </div>
        </div>

        <div class="form-group col-md-12 {{ $data ? return_str( $data['pfx'] , true , 'has-success' , 'has-error' ) : '' }}">  
          <div class="input-group">
            <span class="input-group-addon">.pfx</span>
              <input class="form-control input-sm" readonly="readonly" name="cert_pfx" type="text" value="{{ $pfx }}">     
          </div>
        </div>

        <div class="col-md-12">
          <button type="submit" class="btn btn-block btn-primary btn-flat"> <span class="fa fa-save"></span> Verificar certificados subidos </button>
        </div>

  </form>

  </div>

</div>

@endsection
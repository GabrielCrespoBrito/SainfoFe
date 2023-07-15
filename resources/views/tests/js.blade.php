@extends('layouts.master')

@section('bread')
<li>  Bread </li>
@endsection

@section('css')
@endsection

@section('js')
<script type="module" src="{{ asset('js/test/helpertest.js') }}"></script>
@endsection

@section('titulo_pagina', 'TJs')

@section('contenido')

<form action="#" id="formTest">

  <p data-field="titulo"> </p>

  <p>
    <input type="text" data-field="name" name="name" value="">
  </p>

  <p>
    <select data-field="sexo" value="">
      <option value="H">Hombre</option>
      <option value="M">Mujer</option>
    </select>
  </p>

  <p>
    <label for=""> Noti1
      <input type="checkbox" data-field="noticias" value="not1" name="noticias">
    </label>

    <label for=""> Noti2
      <input type="checkbox" data-field="noticias" value="noti2" name="noticias">
    </label>

    <label for=""> Noti3
      <input type="checkbox" data-field="noticias" value="noti22" name="noticias">
    </label>  

    <label for=""> Noti3
      <input type="checkbox" data-field="noticias" value="noti22" name="noticias">
    </label>  
  </p>

  <p>
    <textarea name="direccion" data-field="direccion"  cols="30" rows="10"></textarea>
  </p>


  <p>
      <label for=""> amarillo
        <input type="radio" data-field="color" name="color" value="amarillo" >
      </label>
  
      <label for=""> azul
        <input type="radio" data-field="color" name="color" value="azul" >
      </label>
  
      <label for=""> rojo
        <input type="radio" data-field="color" name="color" value="rojo" >
      </label>  

    </p>

</form>

<hr>

<p>
<input type="checkbox" value="show" id="show"> Solo mostrar
</p>


<a href="#" id="ejecutar" class="btn btn-xs btn-default"> Ejecutar </a>
<a href="#" data-id="" class="btn btn-xs btn-default"> Ejecutar </a>


<div>
  <embed src="{{ asset('temp/1.pdf') }}" frameborder="0" width="100%" height="400px">
</div>

{{--    <div>
    <iframe src="{{ asset('temp/1.pdf') }}"></iframe>
    </div> --}}

@endsection
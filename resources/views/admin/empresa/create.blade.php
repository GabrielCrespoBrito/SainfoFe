@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Crear Empresa',
'titulo_pagina' => 'Crear Empresa',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','empresa/empresa.js' ]]
])

@slot('contenido')




{{-- Pagina de carga --}}

<div class="empresa-parametros">


<form action="{{ route('admin.empresa.store') }}" id="form-create-empresa" method="post" enctype="multipart/form-data">

  @include('components.block_elemento')

  @csrf

  <div class="info-empresa"> 
    <div class="title-seccion"> Datos </div>   
    @include('empresa.partials.form.data_empresa')
  </div>

    @include('empresa.partials.form.campos_escritorio')

  {{-- <div class="info-parametros">  --}}
    {{-- <div class="title-seccion"> Información facturación </div> --}}
    {{-- @include('empresa.partials.form.data_parametros') --}}
  {{-- </div> --}}


  <div class="acciones-div">
    <a href="{{ route('admin.empresa.index') }}" class="btn link-salir btn-danger btn-flat pull-right">  Salir </a>
    <button type="submit" class="btn btn-primary btn-flat"> <span class="fa fa-save"></span> Guardar </button>
  </div>

</form>

</div>

@endslot
@endview_data
@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Asociar Empresa al Usuario',
'titulo_pagina' => 'Asociar Empresa al Usuario',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js']]
])

@slot('contenido')
  <div class="row">
    <div class="col-md-12">
      {{-- <a href="{{ route('admin.usuarios_documentos.create' , ['id_user' => $user->usucodi , 'id_empresa' => $empresa->id()]) }}" class="pull-right btn btn-default "> <span class="fa fa-copy"></span> Agregar Documento </a> --}}
    </div>
  </div>

  @include('admin.usuarios-empresa.partials.form_show')

@endslot

@endview_data
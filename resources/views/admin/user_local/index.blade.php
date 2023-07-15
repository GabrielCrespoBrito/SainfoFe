@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Usuario Local',
'titulo_pagina' => 'Usuario Local',
'bread' => [ ['Inicio'] ],
'assets' => [
  'libs' => ['datepicker','select2','datatable'],
  'js' => ['helpers.js','user_local/index.js' ]
]
])

@slot('contenido')

  @include('admin.partials.filtros_empresa', ['showLocal' => false])

  @include('components.index_actions', [
    'link' => route('admin.user-local.create', ['empresa_id' => '001'] ), 'route' => route('admin.user-local.create',['empresa_id' => '---'] ),

    'class_name' => 'btn-create-local', 'text' => 'Crear' ])
    
  @component('components.table', [ 'id' => 'datatable', 'url' => route('admin.user-local.search' ), 'thead' => [ 'Usuario' , 'Local' , 'Defecto' , ''] ])

  @endcomponent

  {{-- aws-aws-aws-aws-aws-aws-aws-aws-aws-aws-aws-aws-aws-aws-aws --}}

  @include('partials.modal_eliminate', [ 'url' => route('user-local.destroy', ['usucodi' => 'XX' ] ) ])

  @endslot

@endview_data
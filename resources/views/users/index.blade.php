@view_data([
    'layout' => 'layouts.master',
    'title' => 'Usuarios',
    'titulo_pagina' => 'Usuarios',
    'bread' => [['Usuarios']],
    'assets' => [
        'libs' => ['datepicker', 'select2', 'datatable'],
        'js' => ['helpers.js', 'usuarios/index.js', 'users/index.js']
    ]
])
    @slot('contenido')
        <div class="acciones-div">
            <a href="{{ route('usuarios.form') }}" class="btn btn-primary btn-flat pull-right crear-nuevo-usuario"> <span
                    class="fa fa-plus"></span> Nuevo </a>
        </div>

        @include('users.partials.table')
        {{-- @include('usuarios.partials.modal_usuario', ['isAdmin' => false]) --}}
        @include('partials.modal_eliminate', ['url' => route('usuarios.destroy', 'XX')])
    @endslot

    @slot('js')
        @include('partials.errores')
    @endslot
@endview_data

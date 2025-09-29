@view_data([
    'layout' => 'layouts.master_admin',
    'title' => $title,
    'titulo_pagina' => $title,
    'bread' => [['Inicio']],
    'assets' => [
        'libs' => ['datepicker', 'select2', 'datatable'],
        'js' => ['helpers.js', 'admin/mix/documentos_mix.js']
    ]
])

    @slot('titulo_small')
        <a href="{{ route('admin.documentos.index') }}" class="link-pendientes"> <span class="fa fa-list-ul"></span> Todos </a>
        @if ($empresasAll)
            <a href="{{ route('admin.documentos.pending') }}" class="link-pendientes"> <span class="fa fa-external-link"></span>
                Pendientes </a>
        @else
            <a href="{{ route('admin.documentos.pending', ['empresasAll' => true]) }}" class="link-pendientes"> <span
                    class="fa fa-list-ul"></span> Todas las Empresas </a>
        @endif
    @endslot

    @slot('contenido')
        @if ($hasPendientes)
            @if ($empresasAll)
                {{--  --}}
                <div class="filtros">
                    @include('admin.partials.filtros_empresa')
                    @include('admin.documentos.partials.botones_pendientes', ['isPendiente' => false])
                </div>
                {{--  --}}
            @else
                @include('admin.partials.filtros', ['isPendiente' => true])
                @include('admin.documentos.partials.botones_pendientes', ['isPendiente' => false, 'consultStatus' => true])
            @endif


            <hr>
            @include('admin.documentos.partials.table', ['isPendiente' => true])
        @else
            @include('admin.documentos.partials.no_data', [
                'name' => 'Documentos',
                'route' => route('admin.actions.update_ventas_acciones'),
            ])
        @endif
    @endslot

@endview_data

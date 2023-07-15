@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Toma de Inventario',
  'titulo_pagina'  => 'Toma de Inventario', 
  'bread'  => [ ['Toma de Inventario'] ],
  'assets' => ['libs' => ['datatable', 'download'],'js' => ['helpers.js','toma_inventario/index.js', 'toma_inventario/import.js']]
])

{{-- <script src="{{ asset('plugins/download/download.js') }}"> </script>   --}}

@slot('contenido')

  @push('js') 
    @include('partials.errores')
  @endpush 

  @include('toma_inventario.partials.filter')
  @include('toma_inventario.partials.table')

  @include('partials.modal_eliminate', ['url' => route('toma_inventario.destroy' , 'XX') ])

  @include('toma_inventario.partials.modal_importacion', ['url' => route('toma_inventario.destroy' , 'XX') ])


@endslot  

@endview_data
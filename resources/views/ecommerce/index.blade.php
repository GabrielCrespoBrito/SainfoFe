@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Tienda - Cotizaciones',
  'titulo_pagina'  => 'Tienda', 
  'bread'  => [ ['Tienda - Cotizaciones'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','ecommerce/mix/scripts.js']]
])

  {{-- 'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','compras/mix/crud_mod.js', 'clientes/scripts.js'  ]] --}}

@slot('titulo_small')
  <span class="tienda-div"> <a href="{{ $tienda_url }}" class="btn btn-xs btn-primary" target="_blank"> imduper.com.pe </a> </span>
@endslot

@slot('contenido')

  @push('js') 
    @include('partials.errores')
  @endpush 

  {{-- @include('ecommerce.partials.filter')
  

  @if( !$success )
    @include('ecommerce.partials.error_coneccion') 
  @else
    @include('ecommerce.partials.table') 
  @endif

  @include('partials.modal_eliminate', ['url' => route('tienda.destroy' , 'XX') ])

  <hr> --}}

  @include('ecommerce.partials.container',['import' => 0, 'search' => 1]) 

@endslot  

@endview_data
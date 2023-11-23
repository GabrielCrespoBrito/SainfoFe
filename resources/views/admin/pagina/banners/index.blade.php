@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Banners',
'titulo_pagina' => 'Banners',
'bread' => [ ['Inicio'] ],
'assets' => ['js' => ['helpers.js']]

])

@slot('contenido')

  <div class="row">
    <div class="col-md-12">
      <a class="btn btn-flat btn-primary pull-right" href="{{ route('admin.pagina.banners.create') }}">  <span class="fa fa-plus"></span> Nuevo </a>
    </div>
  </div>

{{-- id, representante, cargo, testimonio_text, imagen, created_at, updated_at --}}
    @component('components.table', [ 'id' => 'table_canje' , 'thead' => [ 'Imagen Principal', 'Imagen Para Mobil', 'Nombre', '' ]])
      @slot('body')
        @foreach( $banners as $banner )
          <tr>
            <td> <img style="width:100px" src="{{ $banner->pathImage() }}" alt="" class="rounded-circle img-fluid"></td>
            
            <td> <img style="width:100px" src="{{ $banner->pathImageMobil() }}" alt="" class="rounded-circle img-fluid"></td>

            <td> {{ $banner->nombre }} </td>

            <td> 
              <a class="btn btn-flat btn-default btn-xs" href="{{ route('admin.pagina.banners.edit', $banner->id) }}"><span class="fa fa-pencil"></span>  </a>
              
              <form method="post" action="{{ route('admin.pagina.banners.destroy', $banner->id) }}">
                @csrf
                <button class="btn btn-flat btn-danger btn-xs" type="submit"> <span class="fa fa-trash"></span>
                </button>
              </form>
            </td>
            
          </tr>
        @endforeach
      @endslot

    @endcomponent


@endslot
@endview_data

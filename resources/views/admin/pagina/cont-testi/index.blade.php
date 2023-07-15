@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Testimonios Contabilidad',
'titulo_pagina' => 'Testimonios Contabilidad',
'bread' => [ ['Inicio'] ],
'assets' => ['js' => ['helpers.js']]

])

@slot('contenido')

  <div class="row">
    <div class="col-md-12">
      <a class="btn btn-flat btn-primary pull-right" href="{{ route('admin.pagina.contabilidad-testi.create') }}">  <span class="fa fa-plus"></span> Nuevo </a>
    </div>
  </div>

{{-- id, representante, cargo, testimonio_text, imagen, created_at, updated_at --}}
    @component('components.table', [ 'id' => 'table_canje' , 'thead' => [ '',  'Representante', 'Cargo', 'Testimonio', '' ]])
      @slot('body')
        @foreach( $testimonios as $testimonio )
          <tr>
            <td> <img style="width:100px" src="{{ $testimonio->pathImage() }}" alt="" class="rounded-circle img-fluid"></td>
            <td> {{ $testimonio->representante }} </td>
            <td> {{ $testimonio->cargo }} </td>
            <td> {{ $testimonio->testimonio_text }} </td>
            <td style="display:flex"> 
              <a class="btn btn-flat btn-default btn-xs" href="{{ route('admin.pagina.contabilidad-testi.edit', $testimonio->id) }}"><span class="fa fa-pencil"></span>  </a>
              
              <form method="post" action="{{ route('admin.pagina.contabilidad-testi.destroy', $testimonio->id) }}">
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

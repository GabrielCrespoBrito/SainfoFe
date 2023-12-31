@php
    $create = !$banner->exists;
    $route = $create ? route('admin.pagina.banners.store') : route('admin.pagina.banners.update', $banner->id);
@endphp

@include('partials.errors_html')

<form enctype="multipart/form-data" method="post" action="{{ $route }}">
    @csrf
    <div class="row">

        <div class="form-group col-md-6">
            <label htmlFor="representante">Nombre</label>
            <input required type="text" name="nombre" id="nombre" class="form-control"
                value="{{ old('nombre', $banner->nombre) }}" placeholder="nombre" />
        </div>

{{-- 
        <div class="col-md-6 form-group">
            <label>Imagen</label>
            <input name="imagen" {{ $create ? 'required' : '' }} type="file" class="form-control" />
        </div> --}}

    </div>


    <div class="row">


        <div class="col-md-6 form-group">
            <label>Imagen Principal </label>
            <input name="imagen" {{ $create ? 'required' : '' }} type="file" class="form-control" />
        </div>

        <div class="col-md-6 form-group">
            <label>-</label>
            <div> <img style="width:100px" src="{{ old('imagen', $banner->pathImage()) }}" alt=""/> </div>
        </div>

    </div>



    <div class="row">


        <div class="col-md-6 form-group">
            <label>Imagen Mobil <span style="color:gray; margin-left:6em"> Tamaño Recomendado 600x628 </span> </label>
            <input name="imagen_mobile" {{ $create ? 'required' : '' }} type="file" class="form-control" />
        </div>

        <div class="col-md-6 form-group">
            <label>-</label>
            <div> <img style="width:100px" src="{{ old('imagen', $banner->pathImageMobil()) }}" alt=""/> </div>
        </div>

    </div>


    <div class="row">

        <div class="col-md-12">

            <button class="btn btn-flat btn-primary" type="submit">
                Guardar
            </button>

            <a href="{{ route('admin.pagina.banners.index') }}" class="btn btn-flat btn-danger pull-right"> Cancelar
            </a>
        </div>
    </div>

</form>

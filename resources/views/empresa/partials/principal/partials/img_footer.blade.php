<div class="row">

  <div class="form-group {{ $errors->has('img_footer') ? 'has-error' : '' }} col-md-12">
    <div class="input-group">
      <span class="input-group-addon">Imagen Footer </span>
      <input class="form-control input-sm" name="img_footer" type="file" value="">
    </div>

    <div class="text-right">
      <span class="imagen-dimenciones">Dimenciones maximas de la imagen es {{ $logos_dimenciones['footer']['width'] . ' x ' . $logos_dimenciones['footer']['height'] }} </span>
    </div>

    @if($empresa->hasImgFooter())
    <div class="form-group imagen_div">
      <img class="img_empresa img_logo_secundario" src="{{ $empresa->img_footer }}">
    </div>
    @endif
    
    <div class="form-group imagen_remove mt-x2">
      <a data-form_action="{{ route('admin.empresa.delete_logo', [ 'id_empresa' => $empresa->id() , 'logo' => 5 ] ) }}" href="#" class="btn btn-xs btn-danger delete_logo"> Quitar imagen </a>

      <a href="{{ route('admin.empresa.logo_footer_sainfo', [ 'id_empresa' => $empresa->id()] ) }}" class="btn btn-xs btn-primary pull-right"> Poner Logo Sainfo </a>

    </div>


  </div>

</div>
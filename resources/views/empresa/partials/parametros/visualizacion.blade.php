@php
$route = $area_admin ? 
route('admin.empresa.update_visual', $empresa->empcodi ) :
route('empresa.update_visual', $empresa->empcodi )

@endphp

<form action="{{ $route }}" method="post" enctype="multipart/form-data">

  {{ csrf_field() }}

  @include('empresa.partials.principal.partials.logos_principales')
  @include('empresa.partials.principal.partials.logos_secundarios')
  @include('empresa.partials.principal.partials.img_footer')


  <div class="row">
    <div class="col-md-12">
      <button type="submit" value="send" class="btn btn-flat btn-primary"> <span class="fa fa-save"></span> Guardar </button>
    </div>
  </div>

</form>
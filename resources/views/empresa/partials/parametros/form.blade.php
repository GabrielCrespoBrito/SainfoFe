@php
$area_admin = $area_admin ?? true; 

$route = $area_admin ? 
route('admin.empresa.update_parametros', $empresa->empcodi ) :
route('empresa.update_parametros', $empresa->empcodi );

$routeModulo = $area_admin ? 
route('admin.empresa.update_modulos', $empresa->empcodi ) :
route('empresa.update_modulos', $empresa->empcodi )


@endphp

<div class="empresa-parametros">

  @include('empresa.partials.parametros.form_modulos', ['routeModulo' => $routeModulo])

  <hr>

  <form action="{{ $route }}" method="post">
    {{ csrf_field() }}
    <div class="info-empresa">	
      @include('empresa.partials.parametros.data')
    </div>    

    @include('empresa.partials.parametros.botones')

  </form>

</div>
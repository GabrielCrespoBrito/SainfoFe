<div class="nav-tabs-custom nav-custom-2">
  <ul class="nav nav-tabs">
    <li class="active"> <a href="#informacion-basica" data-toggle="tab" aria-expanded="true">Información principal </a></li>
    <li class=""> <a href="#parametros" data-toggle="tab" aria-expanded="false"> Caracteristicas </a></li>
    <li class=""> <a href="#caracteristica_new" data-toggle="tab" aria-expanded="false"> Agregar Caracteristicas </a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="informacion-basica">
      @include('admin.planes.partials.form_principal')
    </div>
    <div class="tab-pane" id="parametros">
      @include('admin.planes.partials.form_bondades')
    </div>
    <div class="tab-pane" id="caracteristica_new">
      @include('admin.planes.partials.form_caracteristica_new')
    </div>


  </div>
</div>
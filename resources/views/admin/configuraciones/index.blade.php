@view_data([
'layout' => 'layouts.master_admin' ,
'title' => 'Configuración',
'titulo_pagina' => 'Configuración',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','admin/mix/documentos_mix.js' ]]
])

@slot('contenido')


@include('partials.errors_html')

<!-- Tab -->
<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"> Configuraciones </a></li>
    <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"> Comandos </a></li>
  </ul>

  <!-- .tab-content -->
  <div class="tab-content">

    <!-- .tab-pane -->
    <div class="tab-pane active" id="tab_1">
      @include('admin.configuraciones.partials.form_settings')
    </div>
    <!-- /.tab-pane -->

    <!-- .tab-pane -->
    <div class="tab-pane" id="tab_2">
      @include('admin.configuraciones.partials.comandos')
    </div>
    <!-- /.tab-pane -->

  </div>
  <!-- /.tab-content -->

</div>
<!-- Tab -->

@endslot

@endview_data
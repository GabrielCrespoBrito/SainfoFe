@view_data([
'layout' => 'layouts.master',
'title' => 'Parametros Empresa',
'titulo_pagina' => 'Parametros Empresa' ,
'bread' => [ ['Empresas' , route('admin.empresa.index') ], ['Empresas' , route('admin.empresa.index') ] ],
'assets' => ['libs' => ['datepicker','select2'],'js' => ['helpers.js' , 'empresa/empresa.js' ]]
])

@slot('contenido')
@include('partials.errors_html')

<!-- Tab -->
<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Información principal </a></li>
    <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Parametros</a></li>
  </ul>

  <!-- .tab-content -->
  <div class="tab-content">

    <!-- .tab-pane -->
    <div class="tab-pane active" id="tab_1">
      @include('empresa.partials.principal.form')
    </div>
    <!-- /.tab-pane -->

    <!-- .tab-pane -->
    <div class="tab-pane" id="tab_2">
      @include('empresa.partials.parametros.form')
    </div>
    <!-- /.tab-pane -->

  </div>
  <!-- /.tab-content -->

</div>
<!-- Tab -->

@endslot
@endview_data
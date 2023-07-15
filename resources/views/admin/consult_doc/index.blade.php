@view_data([
'layout' => 'layouts.master_admin' ,
'title' => 'Consultar Documentos',
'titulo_pagina' => 'Consultar Documentos',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js', 'admin/documentos_consult.js' ]]
])

@slot('contenido')

@include('admin.partials.filtros_empresa', ['showLocal' => false])

  @php
  $empresa = null;
  @endphp


<!-- Tab -->
<div class="nav-tabs-custom mt-x20">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"> Documento </a></li>
    <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"> Resumenes  </a></li>
  </ul>

  <!-- .tab-content -->
  <div class="tab-content">

    <!-- .tab-pane -->
    <div class="tab-pane active" id="tab_1">
      @include('admin.consult_doc.partials.form_documento')
    </div>
    <!-- /.tab-pane -->


    <!-- .tab-pane -->
    <div class="tab-pane" id="tab_2">
      @include('admin.consult_doc.partials.form_resumen')
    </div>
    <!-- /.tab-pane -->

  </div>
  <!-- /.tab-content -->

</div>
<!-- Tab -->




@endslot

@endview_data
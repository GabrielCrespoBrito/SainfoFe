<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    
    <li class="active">
      <a href="#informacion-basica" data-toggle="tab" aria-expanded="true">Información Basica </a>
    </li>

    @if($form_sunat)
    <li> <a href="#sunat" data-toggle="tab" aria-expanded="false"> Sunat </a></li>
    @endif

    @if($form_visualizacion)
    <li> <a href="#visual" data-toggle="tab" aria-expanded="false"> Visualización </a></li>
    @endif

    @if($form_parametros)
    <li> <a href="#parametros" data-toggle="tab" aria-expanded="false">Parametros</a></li>
    @endif
    
    @if($form_acciones)
    <li> <a href="#acciones" data-toggle="tab" aria-expanded="false">Acciones</a></li>
    @endif

    @if($form_certificado)
    <li> <a href="#certificado" data-toggle="tab" aria-expanded="false">Certificado   </a></li>
    @endif

    @if($form_tienda)
    <li> <a href="#tienda" data-toggle="tab" aria-expanded="false">Tienda  </a></li>
    @endif


  </ul>

  <!-- .tab-content -->
  <div class="tab-content">

    <div class="tab-pane active" id="informacion-basica">
      @include('admin.empresa.partials.form_basic2' )
    </div>

    @if($form_sunat)
    <div class="tab-pane" style="overflow:hidden" id="sunat">
      @include('empresa.partials.parametros.sunat')
    </div>
    @endif

    @if($form_visualizacion)
    <div class="tab-pane" style="overflow:hidden" id="visual">
      @include('empresa.partials.parametros.visualizacion')
    </div>
    @endif

    @if($form_parametros)
    <div class="tab-pane" style="overflow:hidden" id="parametros">
      @include('empresa.partials.parametros.form')
    </div>
    @endif

    @if($form_acciones)
    <div class="tab-pane" style="overflow:hidden" id="acciones">
      @include('admin.empresa.partials.acciones' )
    </div>
    @endif

    @if($form_certificado)
    <div class="tab-pane" style="overflow:hidden" id="certificado">
      @include('empresa.partials.parametros.form_certificado')
    </div>
    @endif

    @if($form_tienda)
    <div class="tab-pane" style="overflow:hidden" id="tienda">
      @include('empresa.partials.parametros.form_tienda')
    </div>
    @endif

  </div>
  <!-- /.tab-content -->

</div>


{{-- storeCertificado --}}
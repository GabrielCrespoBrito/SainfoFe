<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    
    <li class="active">
      <a href="#informacion-basica" data-toggle="tab" aria-expanded="true">Información Basica </a>
    </li>

    @if($form_sunat)
    <li> <a href="#sunat" data-toggle="tab" aria-expanded="false"> Sunat </a></li>
    @endif

    <li> <a href="#ordenes" data-toggle="tab" aria-expanded="false"> Ordenes Pago. </a></li>

    <li> <a href="#acciones" data-toggle="tab" aria-expanded="false"> Acciones </a></li>


  </ul>

  <!-- .tab-content -->
  <div class="tab-content">

    <div class="tab-pane active" id="informacion-basica">
      @include('admin.empresa.partials.form_basic2', ['campo_escritorio' => true, 'route' => route('admin.empresa.update_basic_escritorio', ['id' => $empresa->id()])] )
    </div>

    @if($form_sunat)
    <div class="tab-pane" style="overflow:hidden" id="sunat">
      @include('empresa.partials.parametros.sunat')
    </div>
    @endif

    <div class="tab-pane" style="overflow:hidden" id="ordenes">
      @include('empresa.partials.parametros.ordenes')
    </div>

    <div class="tab-pane" style="overflow:hidden" id="acciones">
      @include('admin.empresa.partials.acciones')
    </div>

  </div>
  <!-- /.tab-content -->

</div>


{{-- storeCertificado --}}
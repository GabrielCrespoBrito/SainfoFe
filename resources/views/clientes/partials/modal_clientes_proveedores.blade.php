@php
  $title = $title ?? 'Crear Cliente';
  $defaultEntity = $defaultEntity ?? 'C';
  $closedTipo = $closedTipo ?? false;
  $clean = $clean ?? true;
@endphp

@component('components.modal', ['id' => 'modalCliente', 'size' => 'modal-md' , 'title' => $title, 'defaultEntity' =>   $defaultEntity ])


  {{-- @dd( $ruc, $telf, $email ); --}}

  @php
    $tipos_cliente = cacheHelper('tipocliente.all');
    $tipos_documentos_clientes = cacheHelper('tipodocumento.all')    
  @endphp

  @push('js')
  <script>
    var url_crear_cliente = "{{ route('clientes.create') }}";
    var url_consulta_sunat = "{{ route('clientes.consulta_ruc') }}";
    var url_consulta_codigo = "{{ route('clientes.consulta_codigo') }}";
</script>    

  @endpush

  @slot('body')   

<form 
data-url_store="{{ route('clientes.create') }}" 
data-url_ruc="{{ route('clientes.consult_ruc') }}"
data-url_dni="{{ route('clientes.consult_dni') }}"

id="form-cliente" 
data-clean="{{ $clean }}" 
onsubmit="return false;">

      <!-- tab -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Información principal</a></li>
          <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Complementaria</a></li>
          <li><a href="#tab_3" data-toggle="tab">Avalista</a></li>
        </ul>
        <div class="tab-content">
          <!-- Tab info principal -->
          <div class="tab-pane active" id="tab_1">
            @include('clientes.partials.form.principal')
          </div>

          <!-- Tab info adiconal -->          
          <div class="tab-pane" id="tab_2">
            @include('clientes.partials.form.adicional')
          </div>

          <!-- Tab avalista -->          
          <div class="tab-pane" id="tab_3">
            @include('clientes.partials.form.avalista')            
          </div>

        </div>
      </div>
      <!-- /.tab-content -->

      <div class="row">
        <div class="col-xs-12">
          <a class="btn btn-primary btn-flat send_cliente_info"><span class="fa fa-save"></span> Guardar</a>
        </div>
      </div>

        <!-- /.col -->
    </form>



 
  @endslot

@endcomponent
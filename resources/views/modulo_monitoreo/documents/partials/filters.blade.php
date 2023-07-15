@php

@endphp

<div class="row">  
  @include('modulo_monitoreo.documents.partials.empresas' , [ 'routeName' => 'monitoreo.empresas.docs' ])
</div>

<div class="row">
  {{-- Mes  --}}
  <div class="col-md-4 form-group">   @include('components.specific.select_mes')  </div>

  {{-- Serie --}}
  @include('modulo_monitoreo.documents.partials.serie_document', ['size' => 'col-md-4'] )

  {{-- Codigo --}}
  @if($showCode)
    <div class="col-md-4 form-group">   
      @include('modulo_monitoreo.documents.partials.code')
  </div>
  @endif


</div>
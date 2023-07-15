<form action="{{ $route }}" id="form-empresa" method="POST">
  @csrf
  {{ $method }}
  
  @include('modulo_monitoreo.empresas.partials.info')
  @include('modulo_monitoreo.empresas.partials.certs')
  @include('modulo_monitoreo.empresas.partials.series')

  <div class="acciones-div">
    
    <input type="submit" name="createNew" value="Guardar" class="btn btn-primary btn-flat">
    <a href="{{ route('monitoreo.empresas.index') }}" class="btn btn-danger btn-flat"> Salir </a>

    @if( ! $isCreate)
      <a href="{{ route('monitoreo.empresas.docs' , $empr->id ) }}" class="btn pull-right btn-default btn-flat"> Documentos </a> 
      <a href="{{ route('monitoreo.empresas.process_docs' , $empr->id ) }}" class="btn pull-right btn-default btn-flat"> Procesar  </a>
    @endif
    
  </div>  
  
</form>

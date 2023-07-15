<div class="col-md-12">
  @include('components.btn_procesando')

  <div class="row">    
    <div class="col-md-3">
      <select name="tipo" class="form-control input-sm text-center">
        <option  value="historico"> Historico </option>
        <option  value="pendientes" selected="selected"> Pendientes </option>
        <option  value="vencidas"> Vencidas </option>        
      </select>
    </div>

    <div class="col-md-3">
      <select name="user" class="form-control input-sm text-center">
        <option value="todos"> Todos </option>
        @foreach( $users as $user )
          <option value="{{ $user->usucodi }}"> {{ $user->usunomb }} </option>
        @endforeach
      </select>
    </div>
    
    <div class="col-md-5 custom-select2">
      <input type="hidden" id="cliente_ruc" name="cliente_ruc" value="">
      <select data-type="{{ $type }}" data-placeholder="Buscar {{ $entidad  }}" style="position: absolute; visibility: hidden;" id="cliente" name="cliente" class="form-control  input-sm text-center">  
        </select>
    </div>

    <div class="col-md-1">
      <a id="reloadTable" href="#" class="btn btn-xs btn-primary"> <span class="fa fa-repeat"></span> </a>
    </div>
  </div>
  
</div>

<div class="col-md-8">
  <span>
  <input type="radio" name="agrupacion" value="cliente">
  <label> Por grupo {{ $entidad }} </label>
  </span>
  <span>
  <input checked="checked" type="radio" name="agrupacion" value="vencimiento">
  <label> Por fecha de vencimiento </label>
  </span>    
</div>

<div class="col-md-4">
  @if( ! $isPorPagar )  
  <a href="#" class="btn pull-right btn-primary enviar btn-sm"> <span class="fa fa-send"></span> Enviar Email </a>  
  @endif
  <a href="#" class="btn pull-right btn-default imprimir btn-sm"> <span class="fa fa-print"></span> Imprmir </a>  

  <select name="formato" class="btn btn-sm pull-right btn-default formato"> 
    <option value="pdf"> PDF </option>    
    <option value="excell"> Excell </option>    
  </select>  

</div>
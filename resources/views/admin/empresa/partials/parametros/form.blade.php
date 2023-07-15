<div class="empresa-parametros">
  <form action="{{ route('empresa.store_option' , $empresa->id()) }}" method="post">
    {{ csrf_field() }}
    <div class="info-empresa">	
      @include('empresa.partials.parametros.data', ['notFieldShow' => $notFieldShow] )
    </div>    
    @include('empresa.partials.parametros.botones')
  </form>


  <hr>


  <h4> <span class="fa fa-pencil"> </span> Acciones </h4>
  

  <div class="row">

    <div class="col-md-2">
      <form action="{{ route('empresa.update_productos_precios') }}" method="get">
        {{ csrf_field() }}        
        <button class="btn btn-default btn-flat" type="submit"> Actualizar Precios </button>
      </form>
    </div>

    <div class="col-md-3">
      <form action="{{ route('empresa.update_valor_venta') }}" method="get">
        {{ csrf_field() }}
        <button class="btn btn-default btn-flat" type="submit"> Actualizar Valor de Compra/Venta </button>
      </form>
    </div>

    {{-- <div class="col-md-2">
      <form action="{{ route('empresa.update_campo_costos') }}" method="get">
        {{ csrf_field() }}
        <button class="btn btn-default btn-flat" type="submit"> Actualizar campo de costos </button>
      </form>
    </div> --}}

    <div class="col-md-2">
      <form action="{{ route('empresa.update_costos_reales') }}" method="get">
        {{ csrf_field() }}
        <button class="btn btn-default btn-flat" type="submit"> Actualizar Costos Reales </button>
      </form>
    </div>





  </div>



</div>
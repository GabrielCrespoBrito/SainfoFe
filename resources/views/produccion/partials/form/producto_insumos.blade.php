{{-- Title --}}
<div class="row">   
  <div class="form-group col-md-12">  
    <p class="text-center"> <span class="fa fa-box"></span> Productos de Insumos</p>
  </div>
</div>

<div id="container-productos-insumos">

  @if( $active_form )

    @if( $create )
      @include('produccion.partials.form.producto_insumos_create')
    @endif

    @if( $edit )
      @include('produccion.partials.form.producto_insumos_edit')
    @endif

  @endif

</div>

@if( $active_form )

<div class="row">
  <div class="form-group col-md-12 text-center">
    <a href="#" class="btn-nuevo-producto btn btn-flat btn-primary"> <span class="fa fa-plus"></span>  Agregar </a>
  </div>
</div>

@endif

@if( $show )
  @include('produccion.partials.form.producto_insumos_show')
@endif
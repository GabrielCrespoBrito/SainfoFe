@php
  
  $deleteBtn = $deleteBtn ?? false;
  $id = $id ?? "";
  $text = $text ?? "";
  $cantidad = $cantidad ?? 1;
  $nameInputProducto = $nameInputProducto ?? "";
  $nameInputCantidad = $nameInputCantidad ?? "";
  $plantilla = $plantilla ?? false; 
@endphp

<div style="{{ $plantilla ? 'display:none' : '' }}" class="row producto-forms {{ $plantilla ? 'plantilla-product' : '' }}">

  <div class="form-group col-md-8">  
    <div class="input-group">
      <span class="input-group-addon">Producto </span>
        <div class="fixed_position">
        <select id="" data-id="{{ $id }}" required data-text="{{ $text }}" data-url="{{ route('productos.buscar_select2') }}" name="{{ $nameInputProducto }}" style="position:absolute" class="form-control input-sm select2"></select>
        </div>
    </div>
  </div>

  <div class="form-group {{ $deleteBtn ? 'col-md-3' : 'col-md-4' }}">    
    <div class="input-group">
      <span class="input-group-addon">Cantidad</span>
        <input class="form-control input-sm text-center" required name="{{ $nameInputCantidad }}" type="number" min="1"  value="{{ $cantidad }}">     
    </div>
  </div>
  
  @if($deleteBtn)
    <div class="form-group col-md-1">    
      <a href="#" class="btn btn-flat btn-danger btn-block btn-eliminar-producto"> <span class="fa fa-trash"></span>  </a>
    </div>
  @endif

</div>
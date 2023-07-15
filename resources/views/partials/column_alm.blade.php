@php
$isCompra = property_exists($model , 'CpaSdCa');
$campoPorEnviar = $isCompra ? 'CpaSdCa' : 'VtaSdCa';
$campoCantidad = $isCompra ? 'CpaSdCa' : 'VtaCant';

$porEnviar = $model->{$campoPorEnviar};
$cantidad = $model->{$campoCantidad};

if( $porEnviar == 0){
  $message = 'Cerrado';
  $bg = "bg-green";
  $icon = "fa-check";
}

elseif( $porEnviar == $cantidad ){
  $message = 'Pendiente';
  $bg = "bg-gray";
  $icon = "fa-spin fa-spinner";
}

else {
  $message = "Faltante ({$porEnviar})";
  $bg = "bg-yellow";
  $icon = "fa-spin fa-spinner";
}

@endphp

<span class="badge {{ $bg }}"> <span class="fa {{ $icon }}"></span>  {{ $message }} </span>

{{-- 

<span href="#" class="btn  btn-flat btn-xs {{ $bg }}">
  {{ $message }}
</span>

--}}
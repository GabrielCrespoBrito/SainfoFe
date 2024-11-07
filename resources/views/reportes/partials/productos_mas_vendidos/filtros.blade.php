@php
  $conVendedores  = $conVendedores ?? false;
  $descontarPorcVendedor = $descontarPorcVendedor ?? false;
@endphp

<div class="filtros">
<form action="{{ route('reportes.productos_mas_vendidos.pdf') }}" method="post">
@csrf
	@include('reportes.partials.general.fechas')
	@include('reportes.partials.general.locales', ['conVendedores' => $conVendedores])

  @if( $descontarPorcVendedor )
    <div class="row">
    <div class="col-md-12 pl-0" style="margin-bottom:20px">
      <label style="float:right; font-weight:normal"> <input type="checkbox" name="descontar_porc_vendedor" value="1"> Descontar Porc. Vendedor </label>
    </div>
    </div>
  @endif

</form>
</div>
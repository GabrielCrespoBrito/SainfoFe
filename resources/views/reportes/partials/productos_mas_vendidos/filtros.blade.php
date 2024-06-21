@php
  $conVendedores  = $conVendedores ?? false;
@endphp

<div class="filtros">
<form action="{{ route('reportes.productos_mas_vendidos.pdf') }}" method="post">
@csrf
	@include('reportes.partials.general.fechas')
	@include('reportes.partials.general.locales', ['conVendedores' => $conVendedores])
</form>
</div>
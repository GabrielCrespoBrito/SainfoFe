@php
$hasFilters = isset($filtros);
$class_name = $class_name ?? '';
@endphp

{{-- Css --}}
@push('css')
  @include('components.reportes.reporte_basico.css')
@endpush
{{-- Css --}}

{{-- Header --}}
  @include('components.reportes.reporte_basico.header', ['class_name' => $class_name] )
{{-- Header --}}

{{-- Filtros --}}
  @isset($content)
    {!! $content !!}
  @endisset
{{-- Filtros --}}
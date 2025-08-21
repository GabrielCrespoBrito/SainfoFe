@if(auth()->user()->isAdmin())
@php
  $regenerarPdfCotizaciones = session()->get('regenerarPdfCotizaciones', false);
@endphp

  <label for="regenerarPdfCotizaciones">
    <a href="{{ $regenerarPdfCotizaciones ? route('generarPdfs', 0) : route('generarPdfs', 1) }}" class="btn btn-primary btn-xs {{ $regenerarPdfCotizaciones ? 'active' : '' }}">
      {{ $regenerarPdfCotizaciones ? 'Desactivar Regenerar PDF' : 'Activar Regenerar PDF' }}
    </a>
  </label>
@endif
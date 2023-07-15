@php
  $class_name = $class_name ?? '';
@endphp

<div class="header">

  <table class="table-header-nombre " width="100%">
    <tr class="{{ $class_name }}">
      <td with="70%"> <span class="nombre"> {{ $nombre_reporte }} </span> </td>
      <td with="30%"> <span class="fecha"> {{ date('Y-m-d H:m:i') }}</span>  </td>
    </tr>
  </table>
  
  <table class="table-header-informacion" width="100%">
    <tr>

      {{-- Empresa Información --}}
      <td with="200%"> 
        <table width="100%">
            <tr>
              <td> 
                <span class="empresa-nombre""> {{ $nombre_empresa }} </span>
                <span class="empresa-ruc"> {{ $ruc }} </span>
              </td>              
            </tr>
        </table>
      </td>
      {{-- /Empresa Información --}}



    </tr>
    
  </table>  

  @if( $hasFilters )
    {!! $filtros !!}
  @endif


</div>
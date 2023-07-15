
<div class="row">
@php
  $is_guia = isset($is_guia);
@endphp


<div class="col-md-5 text-left">  
  <label class="radio-inline"><input name="tipo_reporte" value="venta" type="radio" checked="checked">{{ $is_guia ? "Guia" : "Venta" }} </label>
  <label class="radio-inline"><input name="tipo_reporte" value="detalle" type="radio"> Detalle</label>
  @if(!$is_guia)
    <label class="radio-inline"><input name="tipo_reporte" value="item" type="radio"> Items</label> 
  @endif
</div>

<div class="col-md-7 text-right">

  <a href="{{ route('home') }}" class="btn btn-danger btn-flat pull-right"> <span class="fa fa-trash"></span> Salir </a>

  @php $tipo_reporte = $is_guia ? 'guia' : 'ventas';  @endphp

  <a href="#" data-href="{{ route( 'reportes.visualizacion' , [
  'tipo_reporte'  => 'tipo', 
  'cliente'       => 'cliente',
  'local'         => 'local',
  'fecha_desde'   => 'fecha_desde',
  'fecha_hasta'   => 'fecha_hasta',
  'tipo_documento'=> 'tipo_documento',
  'serie'         => 'serie',
  'vendedor'      => 'vendedor',
  'reporte'       => $tipo_reporte,  
  ]) }}" 
  class="btn btn-default btn-flat pull-right buscar"> 
    <span class="fa fa-search"></span> Ver 
  </a>

</div>
</div>
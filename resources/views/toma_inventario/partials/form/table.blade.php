@php


$button =
sprintf("<a href='#' data-route='%s' class='btn-xs btn-primary btn-update-stocks'><span class='fa fa-refresh'></span> </a>", route('toma_inventario.update-stocks'));

$stock_actual = $active_form ? sprintf(' Stock.Act %s' , $button) : 'Stock.Act';

$thead = [
'Codigo',
'Marca',
'Descripción',
'Unid.' ,
['class_name' => 'text-right', 'attributes' => [ 'style' => 'text-align:right !important'],'text' => $stock_actual ],
['class_name' => 'loquesea', 'attributes' => [ 'style' => 'text-align:right !important'], 'text' => 'Dif.'] ,
['class_name' => 'loquesea', 'attributes' => [ 'style' => 'text-align:right !important'], 'text' => 'Stock.Nue.'] ,
['class_name' => 'loquesea', 'attributes' => [ 'style' => 'text-align:right !important'], 'text' => 'Costo'] ,
['class_name' => 'loquesea', 'attributes' => [ 'style' => 'text-align:right !important'], 'text' => 'Importe'],

];




if( $active_form ){ array_unshift($thead,'<input type="checkbox" id="input-select-all-def" class="input-select-all">');
}

$classTable = 'sainfo datatable-toma';
$classTable .= $active_form ? ' active' : '';

@endphp

@if( $active_form )
@include('toma_inventario.partials.form.section_acciones')
@endif

@component('components.table' , ['thead' => $thead , 'id' => "items-table" , 'class_name' => $classTable ])

@slot('body')

@foreach( $model->detalles as $detalle )
<tr data-info="{{ $detalle->getInfoJs() }}" data-id="{{ $detalle->ProCodi }}">
  @if($accion == "edit")
  <td><input type="checkbox"></td>
  @endif
  <td class="select-input codi">{{ $detalle->ProCodi  }} </td>
  <td class="select-input marca">{{ $detalle->ProMarc  }} </td>
  <td class="select-input nombre">{{ $detalle->proNomb  }} </td>
  <td class="select-input unidad">{{ $detalle->UnpCodi  }} </td>
  <td class="stock-actual stock_actual">{{ $detalle->ProStock  }} </td>
  <td class="stock-diff">{{ $detalle->getDiff()  }} </td>
  <td class="select-input codi">
    @if($active_form)
    <input class="input-new-stock" type="number" min="0" value="{{ $detalle->ProInve }}">
    @else
    {{ $detalle->ProInve }}
    @endif
  </td>
  <td class="costo">{{ $detalle->ProPUCS }} </td>
  <td class="importe">{{ $detalle->getImporte() }} </td>
</tr>
@endforeach

@endslot



@slot('tfoot')

@if($active_form)
<tr>
  <td colspan="9" style="text-align:center">
    <a href="#" class="btn btn-default border-radius-20 add-products">
      <span class="fa fa-plus"></span>
      Agregar Productos </a>
  </td>
</tr>
@endif

@endslot


@endcomponent
@php
  $class_name = $class_name ?? '';
  $nameTotal = $nameTotal ?? '';
  $codigo = $codigo ?? '';
@endphp

<tr class="{{ $class_name }} border-style-bottom-solid border-style-top-solid border-width-1">
  <td class="bold">{{ $codigo }}</td>
  <td class="bold">{{ $nameTotal }}</td>
  <td class="bold text-align-right border-bottom-solid border-top-solid">{{ $cantidad }}</td>
  <td class="bold text-align-right border-bottom-solid border-top-solid">{{ $importe }}</td>
  <td></td>
</tr>
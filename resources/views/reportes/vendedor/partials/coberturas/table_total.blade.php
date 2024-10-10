@php
  $class_name = $class_name ?? '';
  $nameTotal = $nameTotal ?? '';
  $codigo = $codigo ?? '';
  $total = $total ?? '';
@endphp

<tr class="{{ $class_name }} border-style-bottom-solid border-style-top-solid border-width-1">
  <td style="padding: 0 5px">{{ $codigo }}</td>
  <td style="padding: 0 5px">{{ $nameTotal }}</td>
  <td class="text-align-right border-bottom-solid border-top-solid" style="padding: 0 5px">{{ $cantidad }}</td>
  <td class="text-align-right border-bottom-solid border-top-solid" style="padding: 0 5px">{{ $importe }}</td>
  <td class="text-align-right border-bottom-solid border-top-solid" style="padding: 0 5px">{{ $total }}</td>
  <td></td>
</tr>
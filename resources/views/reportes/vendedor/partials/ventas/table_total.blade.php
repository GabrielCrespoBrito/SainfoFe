@php
  $class_name = $class_name ?? '';
  $nameTotal = $nameTotal ?? '';
@endphp

<tr class="{{ $class_name }} border-style-bottom-solid border-style-top-solid border-width-1">
  <td colspan="3" class="bold">{{ $nameTotal }}</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td class="bold text-align-right border-bottom-solid border-top-solid">{{ $importe }}</td>
  <td class="bold text-align-right border-bottom-solid border-top-solid">{{ $pago }}</td>
  <td class="bold text-align-right border-bottom-solid border-top-solid">{{ $saldo }}</td>
  <td></td>
</tr>
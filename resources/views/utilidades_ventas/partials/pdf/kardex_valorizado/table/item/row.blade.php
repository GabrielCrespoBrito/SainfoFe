<tr class="tr_descripcion">
	<td class="border-left"> {{ $data[0] }} </td>	
	<td> {{ $data[1] }} </td>		
	<td> {{ $data[2] }} </td>
	<td> {{ $data[3] }} </td>
	<td class="border-right"> {{ $data[4] }}</td>



	@php
		$hasData = $data[5] || $data[6] || $data[7];
		$className = $hasData ? 'has-data' : 'no-data';
	@endphp

	<td class="{{ }}"> {{ fixedValueCustom($data[5]) }}</td>
	<td class="{{ $className }}">{{ fixedValueCustom($data[6]) }}</td>
	<td class="border-right class="{{ $className }}""> {{ fixedValueCustom($data[7]) }} </td>
	

	@php
		$hasData = $data[8] || $data[9] || $data[10];
		$className = $hasData ? 'has-data' : 'no-data';
	@endphp

	<td class="{{ $className }}">{{ fixedValueCustom($data[8]) }}</td>
	<td class="{{ $className }}">{{ fixedValueCustom($data[9]) }}</td>
	<td class="border-right {{ $className }}"> {{ fixedValueCustom($data[10]) }} </td>

	<td> {{ fixedValueCustom($data[11])  }} </td>
	<td> {{ fixedValueCustom($data[12]) }} </td>
	<td class="border-right"> {{ fixedValueCustom($data[13]) }} </td>
</tr>
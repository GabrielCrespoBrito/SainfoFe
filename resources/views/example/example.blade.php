<!DOCTYPE html>
<html>
<head>
	<title>Pagina</title>
</head>
<body>

<table width="100%">
	<thead>
		<tr>
			<th>{{ str_random(10) }} </th>
			<th>{{ str_random(10) }} </th>
			<th>{{ str_random(10) }} </th>
			<th>{{ str_random(10) }} </th>
			<th>{{ str_random(10) }} </th>
			<th>{{ str_random(10) }} </th>
			<th>{{ str_random(10) }} </th>
		</tr>
	</thead>
	<tbody>
		@for( $i = 0 ; $i < 1000; $i++ )
		<tr>
			<td> {{ str_random(10) }} </td>
			<td> {{ str_random(10) }} </td>
			<td> {{ str_random(10) }} </td>
			<td> {{ str_random(10) }} </td>
			<td> {{ str_random(10) }} </td>
			<td> {{ str_random(10) }} </td>
			<td> {{ str_random(10) }} </td>
		</tr>
		@endfor

	</tbody>
</table>

</body>
</html>
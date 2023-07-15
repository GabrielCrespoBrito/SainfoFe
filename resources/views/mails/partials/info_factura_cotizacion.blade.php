<table>
	<tr style=" border-bottom: 1px solid black"> 
		<td width="100px"> Tipo: </td>
		<td> <span style="font-weight: bold; border-bottom: 1px solid black"> Cotización </span> </td>
	</tr> 

	<tr style=" border-bottom: 1px solid black"> 
		<td width="100px"> Numero: </td>
		<td> <span style="font-weight: bold; border-bottom: 1px solid black"> {{ $data["cotizacion"]->CotNume }}</span> </td>
	</tr>

	<tr style=" border-bottom: 1px solid black"> 
		<td width="100px"> Monto: </td>
		<td> <span style="font-weight: bold; border-bottom: 1px solid black"> {{ $data['cotizacion']->moneda->monabre }} {{ $data['cotizacion']->cotimpo }} </span> </td>
	</tr> 

	<tr style=" border-bottom: 1px solid black"> 
		<td width="100px"> Fecha emisión: </td>
		<td> <span style="font-weight: bold; border-bottom: 1px solid black"> {{ $data['cotizacion']->CotFVta }} </span> </td>
	</tr> 
</table>
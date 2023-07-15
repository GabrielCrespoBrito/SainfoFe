<table class="table_factura table_orden">
  <thead>
		<tr>
			<td>Codigo</td>
			<td class="text-c"">Descripción</td>
			<td class="text-r">Cantidad</td>
			<td class="text-r">Precio</td>
			<td class="text-r">IGV</td>
			<td class="text-r">Dcto</td>
			<td class="text-r">Total</td>				
		</tr>
	</thead>
	
  <tbody>
		<tr>
			<td>{{ $codigo }}</td>				
			<td class="text-c">{{ $nombre }} </td>				
			<td class="text-r">{{ $cantidad }}</td>
			<td class="text-r">{{ $precio }}</td>
			<td class="text-r">{{ $igv }}</td>
			<td class="text-r">{{ $descuento }}</td>
			<td class="text-r">{{ $total }}</td>
		</tr>


		<tr>
			<td class="total-importe" colspan="7"> IMPORTE TOTAL A PAGAR: <strong>S/{{ $total }}</strong> </td>				
		</tr>

		<tr>
			<td class="cuentas" colspan="7"> 
					<div class="title"> Cuentas: </div>
					<div class="cuenta"> <strong>BBVA</strong> {{ config('app.cuentas.bbva.regular') }} </div>
					<div class="cuenta"> <strong>BBVA</strong> {{ config('app.cuentas.bbva.interbamcaria') }} </div>
			</td>				
		</tr>

		<tr>
			<td class="instruccion" colspan="7"> 
				<div class="mensaje"> <strong> IMPORTANTE: </strong> Envíe su constancia de pago a {{ config('app.mail.pagos') }} o al WhatsApp {{ config('app.phone.contacto') }} ya que es imposible identificar al depositante


				</div>
			</td>				
		</tr>




	</tbody>

</table>
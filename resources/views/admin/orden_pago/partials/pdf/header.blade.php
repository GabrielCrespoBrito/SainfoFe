<table class="table_header">
  <tbody>
		<tr>
			<td width="70%" class="td-empresa-nombre">
					<p class="empresa_nombre"> 
				    	<img style="" src="data:image/png;base64,{{ $logo }}">
						</p>

        <div class="data"> {{ $direccion }} </div> 
        <div class="data"> {{ $telefonos }} </div> 
        <div class="data"> {{ $email }} </div> 
      </td>
      
      <td width="30%" class="td-nro-documento">
        <div class="box-nro-documento">
          <div class="data ruc"> RUC {{ $ruc }} </div> 
          <div class="data nombre-docummento"> ORDEN DE PAGO </div> 
          <div class="data correlativo-documento"> N° <strong>{{ $nrOrden }}</strong>  </div> 
        </div>
      </td>
		</tr>
	</tbody>
</table>
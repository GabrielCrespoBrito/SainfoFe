@php 
  $showExonerada =  $venta2->hasMontoExonerado();
  $showInafecta = $venta2->hasMontoInafecto();
  $showGratuita = $venta2->hasMontoGrauito();
  $showDescuento = $venta2->hasMontoDcto();
	$showPercepcion = $venta2->hasMontoPercepcion();
	$showRetencion = $venta2->hasMontoRetencion();
  $showISC = $venta2->hasMontoISC();
  $showICBPER = $venta2->hasMontoICBPER();
  $is_boleta = $venta2->isBoleta();  
@endphp

<!-- items -->
<div class="totales top">

	<table style="width: 100%">
		
		<tr>
			<td class="left" style="width:50%;">OP Gravadas:</td>
			<td class="right" style="width:25%;">{{ $moneda_abreviatura }}</td>
			<td class="right" style="width:25%;">{{ $venta2->Vtabase }}</td>					
		</tr>	
		
		@if( $showExonerada )
		<tr>
			<td class="left" style="width:50%;">OP Exonerada:</td>
			<td class="right" style="width:25%;">{{ $moneda_abreviatura }}</td>
			<td class="right" style="width:25%;">{{ $venta2->VtaExon }}</td>					
		</tr>	
		@endif

		@if( $showGratuita )
		<tr>
			<td class="left" style="width:50%;">OP Gratuita:</td>
			<td class="right" style="width:25%;">{{ $moneda_abreviatura }}</td>
			<td class="right" style="width:25%;">{{ $venta2->VtaGrat }}</td>					
		</tr>	
		@endif

		@if( $showInafecta )		
		<tr>
			<td class="left" style="width:50%;">OP Inafecta:</td>
			<td class="right" style="width:25%;">{{ $moneda_abreviatura }}</td>
			<td class="right" style="width:25%;">{{ $venta2->VtaInaf }}</td>
		</tr>
		@endif

		@if( $showDescuento )
		<tr>
			<td class="left" style="width:50%;">Descuento total:</td>
			<td class="right" style="width:25%;">{{ $moneda_abreviatura }}</td>
			<td class="right" style="width:25%;">{{ $venta2->VtaDcto }}</td>					
		</tr>		
		@endif

		@if( $showICBPER )
		<tr>
			<td class="left" style="width:50%;">I.C.B.P.E.R</td>
			<td class="right" style="width:25%;">{{ $moneda_abreviatura }}</td>
			<td class="right" style="width:25%;">{{ $venta2->icbper }}</td>					
		</tr>
		@endif

		@if( $showPercepcion )
			@php
				$percepcion_porc =   $venta2->percepcionPorc();
				$percepcion_monto = $venta2->percepcionMonto();
			@endphp
			<tr>
			<td class="left" style="width:50%;">Percepciòn {{ $percepcion_porc }}%:</td>
			<td class="right" style="width:25%;">{{ $moneda_abreviatura }}</td>
			<td class="right" style="width:25%;">{{ fixedValue($percepcion_monto,$decimals) }}</td>					
		</tr>		
    @endif



		@if( $showRetencion )
			@php
      	$retencion_porc = $venta2->retencionPorc();
      	$retencion_monto = $venta2->retencionMonto();
			@endphp
			<tr>
			<td class="left" style="width:50%;">Retención {{ $retencion_porc }}%:</td>
			<td class="right" style="width:25%;">{{ $moneda_abreviatura }}</td>
			<td class="right" style="width:25%;">{{ fixedValue($retencion_monto,$decimals) }}</td>					
		</tr>		
    @endif


		<tr>
			<td class="left" style="width:50%;">I.G.V:</td>
			<td class="right" style="width:25%;">{{ $moneda_abreviatura }}</td>
			<td class="right" style="width:25%;">{{ $venta2->VtaIGVV }}</td>					
		</tr>		

		<tr>
			<td class="left" style="width:50%;">Total Venta:</td>
			<td class="right" style="width:25%;">{{ $moneda_abreviatura }}</td>
			<td class="right" style="width:25%;">{{ $venta2->VtaImpo }}</td>					
		</tr>	

	</table>

</div> 
<!-- /items -->
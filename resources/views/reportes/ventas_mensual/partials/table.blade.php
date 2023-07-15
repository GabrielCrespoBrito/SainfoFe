@php
  $VtabaseTotalGeneral = 0; 									
  $VtaExonTotalGeneral = 0; 
  $VtaInafTotalGeneral = 0; 
  $VtaISCTotalGeneral = 0; 
  $VtaIGVVTotalGeneral = 0; 
	$VtaICBPERTotalGeneral = 0;
  $VtaImpoTotalGeneral = 0; 
  $VtaImpoDolarGeneral = 0; 
  $index = 1;
@endphp

<table width="100%" class="table-items oneline" border="0" cellspacing="0" cellpadding="0">
	<thead>    
    <tr class="header">
			<td rowspan="2"> Fecha emisi&oacuten </td>
			<td rowspan="2"> Fecha Vcto. </td>
			<td colspan="3" style="width: 12%"> Comprobante de pago o Documento </td>
			<td rowspan="2" style="width: 2%" > TE </td>
			<td rowspan="2"> RUC </td>
			<td rowspan="2" class="text-center" style="width: 15%"> Razon social</td>
			<td rowspan="2"> Base imponible</td>
			<td colspan="2" style="width: 13%"> Importe de operaci&oacute;n</td>
			<td rowspan="2" style="width: 3% !important"> isc</td>
			<td rowspan="2" style="width: 4% !important"> igv</td>
			<td rowspan="2" style="width: 4% !important"> icbper</td>
			<td rowspan="2"> Total Soles</td>
			<td rowspan="2" style="width: 3%"> t.c</td>
			<td colspan="4" style="width: 16%"> Doc Orig. que modifica</td>
			<td rowspan="2"> Total USD </td>
			<td rowspan="2"> Est. Sunat </td>
		</tr>	
		<tr class="header">
			<td> TD </td>
			<td> Serie </td>
			<td> Numero </td>			
			<td> Exo</td>
			<td> Inaf</td>
			<td> Fecha</td>
			<td> TD</td>
			<td> Serie</td>
			<td> Numero</td>
		</tr>	
	</thead>

	{{-- Tbody --}}

	<tbody>

			@foreach( $ventas_group as $tipo_documento => $ventas )
      
				<tr class="tipo-documento"> 
					<td colspan="21">
					 {{ $tipo_documento }} {{ nombreDocumento($tipo_documento) }} </td> 
				</tr>

				@php
					$VtabaseDocu = 0;
					$VtaExonDocu = 0;
					$VtaInafDocu = 0;
					$VtaISCDocu  = 0;
					$VtaIGVVDocu = 0;
          $VtaICBPER = 0;
					$VtaImpoDocu = 0;
					$VtaImpoDolarDocu = 0;
				@endphp

        {{-- @dd( $ventas )  --}}
				{{-- Separar en 100 --}}
				@foreach( $ventas->chunk(100)  as $ventass )

					@foreach( $ventass as $venta )
							
						@php


							$venta->VtaDolar = "0.00";
							$isSol = $venta->MonCodi == "01";
				      $isNC = $venta->TidCodi == "07";

              $message = sprintf(
                "Info antes index (%s) VtaOper (%s), Total (%s) EsNotaCredito? (%s)",
                $index, 
                $venta->VtaOper,
                $venta->VtaImpo,
                $isNC ? 'Si' : 'No'

               );
              \Log::info( $message );

							if( $isSol ){
								$venta->VtaTcam = 1;
								$venta->Vtabase = convertNegativeIfTrue($venta->Vtabase, $isNC);
								$venta->VtaExon = convertNegativeIfTrue($venta->VtaExon, $isNC);
								$venta->VtaInaf = convertNegativeIfTrue($venta->VtaInaf, $isNC);
								$venta->VtaISC = convertNegativeIfTrue($venta->VtaISC, $isNC);
								$venta->VtaIGVV = convertNegativeIfTrue($venta->VtaIGVV, $isNC);
								$venta->VtaImpo = convertNegativeIfTrue($venta->VtaImpo, $isNC);
							}
							else {
								$tCambio = $venta->VtaTcam;
								$venta->VtaDolar = convertNegativeIfTrue(fixedValue($venta->VtaImpo), $isNC ); ;
								$venta->Vtabase = convertNegativeIfTrue($venta->Vtabase * $tCambio, $isNC);
								$venta->VtaExon = convertNegativeIfTrue($venta->VtaExon * $tCambio, $isNC);
								$venta->VtaInaf = convertNegativeIfTrue($venta->VtaInaf * $tCambio, $isNC);
								$venta->VtaISC = convertNegativeIfTrue($venta->VtaISC * $tCambio, $isNC);
								$venta->VtaIGVV = convertNegativeIfTrue($venta->VtaIGVV * $tCambio, $isNC);
								$venta->VtaImpo = convertNegativeIfTrue($venta->VtaImpo * $tCambio, $isNC);
							}



                $message = sprintf(
                "Info index despues (%s) VtaOper (%s), Total (%s) EsNotaCredito? (%s)",
                $index,
                $venta->VtaOper,
                $venta->VtaImpo,
                $isNC ? 'Si' : 'No'

                );
                $index++;
                \Log::info( $message );

              $index++;

							$VtabaseDocu += $venta->Vtabase;
							$VtaExonDocu += $venta->VtaExon;
							$VtaInafDocu += $venta->VtaInaf;
							$VtaISCDocu  += $venta->VtaISC;
							$VtaIGVVDocu += $venta->VtaIGVV;
              $VtaICBPER += $venta->icbper;
							$VtaImpoDocu += $venta->VtaImpo;
							$VtaImpoDolarDocu += $venta->VtaDolar;

						@endphp

						<tr> 
							<td class="text-center">{{ newformat_date($venta->VtaFvta) }} </td> 
							<td class="text-center">{{ newformat_date($venta->VtaFVen) }} </td> 
							<td class="text-center">{{ $venta->TidCodi }}</td> 
							<td class="text-center">{{ $venta->VtaSeri }}</td> 
							<td class="text-center">{{ $venta->VtaNumee }}</td> 
							<td class="text-center">{{ $venta->TDocCodi }}</td> 
							<td>{{ $venta->PCRucc }}</td> 
							<td class="text-center" width="200px">{{ $venta->PCNomb }}</td>
                @if($isNC)

                @php
                  // \Log::info("info {$venta->VtaImpo}");
                @endphp
                {{-- @dd( 
                          $venta, 
                          $venta->VtaImpo, 
                          fixedValue($venta->VtaImpo) 
                ); --}}

                @endif


              {{-- {{ $venta->VtaImpo }} --}}

							<td class="text-right">{{ fixedValue($venta->Vtabase) }}</td> 		
              
              @if($isNC)							
                
                {{-- @dd( 
                  $venta, 
                  $venta->VtaImpo, 
                  fixedValue($venta->VtaImpo) 
                ); --}}

              @endif

							<td class="text-right">{{ fixedValue($venta->VtaExon) }}</td> 
							<td class="text-right">{{ fixedValue( $venta->VtaInaf) }}</td> 
							<td class="text-right">{{ fixedValue( $venta->VtaISC) }}</td> 
							<td class="text-right">{{ fixedValue( $venta->VtaIGVV) }}</td>
							<td class="text-right">{{ fixedValue( $venta->icbper) }}</td>
							{{-- <td class="text-right">{{ $venta->VtaImpo }} </td> --}}
							<td class="text-right">{{ $venta->VtaImpo }} </td>

							<td class="text-right">{{ fixedValue( $venta->VtaTcam) }}</td> 
							<td class="text-right">{{ $venta->VtaFVtaR ? newformat_date($venta->VtaFVtaR) : '' }} </td> 			
							<td class="text-right">{{ $venta->VtaTDR }}</td> 
							<td class="text-right">{{ $venta->VtaSeriR }}</td> 
							<td class="text-right">{{ $venta->VtaNumeR }}</td> 			
							<td class="text-right"> {{ $venta->VtaDolar }}  </td>
							<td class="text-right"> {{ $venta->VtaFMail }}  </td>
						</tr>

							@php
								if( $venta->MonCodi == "02" ){
									// dd(" ",  $venta, fixedValue($venta->Vtabase));
								}
							@endphp

					@endforeach					
				@endforeach
				{{-- Separar en 100 --}}

				@php						
					$VtabaseTotalGeneral += $VtabaseDocu;
					$VtaExonTotalGeneral += $VtaExonDocu;
					$VtaInafTotalGeneral += $VtaInafDocu;
					$VtaISCTotalGeneral  += $VtaISCDocu ;
					$VtaIGVVTotalGeneral += $VtaIGVVDocu;
					$VtaICBPERTotalGeneral += $VtaICBPER;


					$VtaImpoTotalGeneral += $VtaImpoDocu;
					$VtaImpoDolarGeneral += $VtaImpoDolarDocu;
				@endphp	



				{{-- Totales --}}
				<tr class="total-documento"> 
					<td colspan="8" class="text-center"> Totales documento: </td> 
					<td class="value text-right"> {{ fixedValue( $VtabaseDocu ) }} </td> 
					<td class="value text-right"> {{ fixedValue( $VtaExonDocu ) }} </td> 
					<td class="value text-right"> {{ fixedValue( $VtaInafDocu ) }} </td> 
					<td class="value text-right"> {{ fixedValue( $VtaISCDocu ) }} </td> 
					<td class="value text-right"> {{ fixedValue( $VtaIGVVDocu ) }} </td>
					<td class="value text-right"> {{ fixedValue( $VtaICBPER ) }} </td>
					<td class="value text-right"> {{ fixedValue( $VtaImpoDocu ) }} </td> 	
					<td class="value text-right" colspan="5"></td>	
					<td class="value text-right"> {{ fixedValue( $VtaImpoDolarDocu ) }} </td> 	
					<td class="value text-right"> </td> 	
				</tr>
		@endforeach

				{{-- Totales --}}
				<tr class="total-documento"> 
					<td colspan="8" class="text-center"> Totales general: </td> 
					<td class="value text-right"> {{ fixedValue( $VtabaseTotalGeneral ) }} </td> 
					<td class="value text-right"> {{ fixedValue( $VtaExonTotalGeneral ) }} </td> 
					<td class="value text-right"> {{ fixedValue( $VtaInafTotalGeneral ) }} </td> 
					<td class="value text-right"> {{ fixedValue( $VtaISCTotalGeneral ) }} </td> 
					<td class="value text-right"> {{ fixedValue( $VtaIGVVTotalGeneral ) }} </td>
					<td class="value text-right"> {{ fixedValue( $VtaICBPERTotalGeneral ) }} </td>         

					<td class="value text-right"> {{ fixedValue( $VtaImpoTotalGeneral ) }} </td> 	
					<td class="value text-right" colspan="5"></td>	
					<td class="value text-right"> {{ fixedValue( $VtaImpoDolarGeneral ) }} </td> 	
					<td class="value text-right"> </td> 						
				</tr>


	</tbody>
</table>
<div class="row_pie">

  <table class="pie" width="100%">
    <tr>
      <td width="100%" class="td_principal text-r" valign="top">
        <strong> Peso total KGM: </strong> <span class="value">{{ $peso_total }}</span>
      </td>
    </tr>
  </table>
  
  <table class="pie" width="100%">
    <tr>
      {{-- @dd($guia) --}}
      <td width="{{ $guia2->isIngreso() ? "100%" : "40%"  }}"  class="td_principal" valign="top">
        <div class="seccion unidad">
          <p class="title_pie"> MOTIVO DE TRASLADO </p>
          <table class="table_motivo" width="100%">

            @foreach( $motivos_traslado->chunk(2) as $motivos )
              <tr>
              @foreach( $motivos as $motivo )
                <td width="50%">
                  <span class="name_motivo {{ $guia['motcodi'] == $motivo->MotCodi ? 'checked' : '' }} "> {{ $motivo->MotNomb }} </span>
                </td>
              @endforeach
              </tr>
            @endforeach
          </table>
        </div>
      </td>
      
      @if(!$guia2->isIngreso())
      <td width="30%" style="text-transform: uppercase; !important" class="td_principal" valign="top">

        <div class="seccion unidad"> 
          <p class="title_pie"> Unidad de transporte </p>

          <table width="100%">
            <tr class="data">
              <td width="50%"> <span class="attr"> Vehiculo Marca:</span> </td>
              <td width="50%"> {{ $guia2->vehiculo->VehMarc }} </td>              
            </tr>
            <tr class="data">
              <td> <span class="attr"> Vehiculo Placa:</span> </td>
              <td> {{ $guia2->vehiculo->VehPlac }} </td>              
            </tr> 
            <tr class="data">
              <td> <span class="attr"> Certificado de Inscripción :</span> </td>
              <td> {{ $guia2->vehiculo->VehInsc }} </td>              
            </tr>  
            <tr class="data">
              <td> <span class="attr"> Licencia conducir:</span> </td>
              <td> {{ $guia2->transportista->TraLice }} </td>              
            </tr>   
            <tr class="data">
              <td> <span class="attr"> Transportista :</span> </td>
              <td> {{  $guia2->transportista->TraNomb }} </td>              
            </tr>   
            <tr class="data">
              <td> <span class="attr"> RUC/DNI:</span> </td>
              <td> {{ $guia2->transportista->TraRucc }} </td>              
            </tr>   
            
          </table>

          <br><br><br><br>
          <div class="seccion unidad empresa_ text-c">
            <p style="text-align:center;border-top:1px solid #999">RECIBI CONFORME</p> 
          </div>
        </div>

      </td>

      <td width="30%" class="td_principal" style="" valign="top">
        <div class="seccion unidad">
          <p class="title_pie"> EMPRESA DE TRANSPORTE </p>
            <p class="data"> 
              <span class="attr"> NOMBRE: </span> 
              <span class="value"> {{ optional($guia2->empresaTransporte)->EmpNomb }} </span> 
            </p>
            <p class="data"> 
              <span class="attr"> RUC.:  </span> 
              <span class="value"> {{ optional($guia2->empresaTransporte)->EmpRucc }}</span> 
            </p>
          <div class="qr">
            <img src="data:image/png;base64, {!! base64_encode($qr)!!} ">            
          </div>
        </div>
      </td>
      @endif
    </tr>
  </table>

</div>
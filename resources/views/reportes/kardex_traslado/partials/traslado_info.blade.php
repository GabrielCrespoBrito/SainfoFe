<tr class="info-traslado">
  <td colspan="6"> 
    {{-- Table --}}
    <table class="table_descripcion" style="background-color: #cccccc" width="100%">
        <tr>

          <td width="50%" class="border-right" style="position: relative;  padding-left:10px;vertical-align: top">

            <div class="titulo"> Guia Salida </div>
            
            <div class="propiedades">
              <span class="nombre">Fecha:</span>
              <span class="valor">{{ $ingreso['fecha_guia_origen'] }} </span>
            </div>

            <div class="propiedades">
              <span class="nombre">Nro Operación:</span>
              <span class="valor">{{ $ingreso['id_guia_origen'] }} </span>
            </div>

            <div class="propiedades">
              <span class="nombre">Numeración:</span>
              <span class="valor">{{ $ingreso['serie_guia_origen'] }} {{ $ingreso['numero_guia_origen'] }} </span>
            </div>
          
          {{--------------------------------------------------------------------------------------------

            <div class="propiedades">
              <span class="nombre">Vehiculo:</span>
              <span class="valor">SIERRA 400 1544D-66D8 </span>
            </div>

            <div class="propiedades">
              <span class="nombre">Transportista:</span>
              <span class="valor">LENIN TRANSPORTE </span>
            </div> --}}

            {{-- <div class="propiedades">
              <span class="nombre">Usuario:</span>
              <span class="valor"> ALEMANA <span>
            </div> 
                        
            ------------------------------------------------------------------------------------------------}}

          </td>

          {{-- TD --}}
          <td width="50%" style="padding-left:10px; vertical-align: top;">

            <div class="titulo"> Guia Ingreso </div>

            <div class="propiedades">
              <span class="nombre">Fecha:</span>
              <span class="valor">{{ $salida['fecha_guia_destino'] }} </span>
            </div>

            <div class="propiedades">
              <span class="nombre">Nro Operación:</span>
              <span class="valor">{{ $salida['id_guia_destino'] }} </span>
            </div>

            <div class="propiedades">
              <span class="nombre">Numeración:</span>
              <span class="valor">{{ $salida['serie_guia_destino'] }} {{ $salida['numero_guia_destino'] }} </span>
            </div>

          </td>
          {{-- /TD --}}

        </tr>
    </table>
    {{-- /Table --}}    
  </td>
</tr>
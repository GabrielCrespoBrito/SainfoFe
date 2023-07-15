<div class="div_cliente">
  <table class="table_cliente" width="100%">
    <tr>

      {{-- Cliente --}}
      <td class="cliente-data" width="50%">
        <p>
          <span class="info info-nombre">Razon Social:</span> 
          <span class="info info-valor"> {{ $razon_social_cliente }} </span> 
        </p>
        
        <p>
          <span class="info info-nombre">RUC</span> 
          <span class="info info-valor"> {{ $ruc_cliente }} </span> 
        </p>

        <p>
          <span class="info info-nombre">Dirección</span> 
          <span class="info info-valor"> {{ $direccion_cliente }} </span> 
        </p>
        
      </td>
      {{-- Cliente --}}


      {{-- Datos --}}
      <td class="documento-data" width="50%">
        <p>
          <span class="info info-nombre">Fecha emisión:</span> 
          <span class="info info-valor"> {{ $fecha_emision }} </span> 
        </p>
        
        <p>
          <span class="info info-nombre">Fecha vencimiento:</span> 
          <span class="info info-valor"> {{ $fecha_vencimiento }} </span> 
        </p>        

        <p>
          <span class="info info-nombre">Moneda</span> 
          <span class="info info-valor"> Soles </span> 
        </p>

      </td>
      {{-- /Datos --}}


    </tr>


    {{-- Advertencia --}}

        <tr>

      {{-- Cliente --}}
      <td class="cliente-data" colspan="2" width="50%">
        <p class="advertencia">  *Este documento <strong> NO ES UN COMPROBANTE DE PAGO </strong> , se emitirá una FACTURA ELECTRÓNICA luego del pago. Revisar los datos del RUC y DENOMINACIÓN de esta orden ya que una vez generado el comprobante no puede ser cambiado.
     </p>
           
      </td>
      {{-- Cliente --}}




    </tr>


    {{-- /Advertencia --}}

  


</table>
</div>

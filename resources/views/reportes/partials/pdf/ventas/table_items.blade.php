<div class="container div_items">
  <table class="table_items" id="table_venta" width="100%">      
    <!-- items -->
    @foreach( $ventas_groups as $id => $ventas_group )
    
    <tr class="documento_tr" style="padding: 10px 0;">
      <td colspan="14"> --- <strong>{{ $ventas_group->first()->tipo_documento->TidNomb }} ---

        </strong>
        {{-- <span class="tipo_documento_id"> {{ $id }} </span>  --}}
      </td>  
    </tr>

      @foreach( $ventas_group as $venta )
        <tr class="body_tr_item">
          <td colspan="14" style="border-bottom: 2px solid #ccc">
            <table width="100%">
              <tr class="tr_head-venta">
                <td colspan="2"><strong>{{ $venta->VtaOper}}</strong></td>
                <td colspan="3"><strong>{{ $venta->VtaNume }}</strong></td>
                <td colspan="6">Clientes: <strong>{{ substr($venta->cliente->PCNomb, 0,20) }}</strong></td>                
                <td colspan="3">Usuario: <strong> {{ $venta->User_Crea }} </strong></td>
              </tr>

              <tr class="tr_head-venta-2">
                <td colspan="3">Fecha:  <strong> {{ $venta->VtaFvta }} </strong></td>
                <td colspan="3">Hora:  <strong> {{ $venta->VtaHora }} </strong></td>
                <td colspan="3">Vendedor:  <strong> {{ $venta->Vencodi }} </strong></td>                
                <td colspan="3" class="estado_documento">{{ $venta->VtaEsta }}</td>
                <td colspan="2">Condicion: <strong> {{ $venta->forma_pago->connomb }}</strong></td>                  
              </tr> 

              <tr class="tr_item-head">
                <td colspan="1"><strong>Item</strong></td>
                <td colspan="1"><strong>Codigo</strong></td>
                <td colspan="1"><strong>Unidad</strong></td>
                <td colspan="6"><strong>Descripción</strong></td>
                <td colspan="1"><strong>Cantidad</strong></td>
                <td colspan="1"><strong>Precio</strong></td>
                <td colspan="1"><strong>Dcto.</strong></td>
                <td colspan="2"><strong>Importe</strong></td>
              </tr>

              @foreach( $venta->items as $item )
              <tr class="tr_item-elemento">
                <td colspan="1"> {{ $item->DetItem }} </td>
                <td colspan="1"> {{ $item->DetCodi }} </td>
                <td colspan="1"> {{ $item->DetUnid }} </td>
                <td colspan="6"> {{ $item->DetNomb }} </td>
                <td colspan="1"> {{ $item->DetCant }} </td>
                <td colspan="1"> {{ $item->DetPrec }} </td>
                <td colspan="1"> {{ $item->DetDcto }} </td>
                <td colspan="2"> {{ $item->DetImpo }} </td>
              </tr>
              @endforeach 

            </table>              
          </td>       
        </tr>
      @endforeach

    @endforeach
    <!-- /items -->

  </table>
</div>
<p> Estimado cliente, {{ $data['cliente_nombre'] }} (<span style="font-weight: bold" >{{ $data['cliente_ruc'] }}</span>) </p>

<div class="info">Le informamos a usted de los siguientes documentos que requiren su atención </div>

<div class="table">

  <table style="text-align: center">
    <tr>
      <th> Codigo </th>      
      <th> Tipo Documento </th>
      <th> Numero </th>
      <th> Monto </th>
      <th> Cliente </th>
    </tr>
    @foreach( $documentos as $documento )
      @foreach( $documento->detalles as $docu )
      <tr>
        <td >{{ $docu->VtaOper }}</td>
        <td>{{ $docu->venta->TidCodi }}</td>
        <td>{{ $docu->venta->VtaNumee }}</td>
        <td>{{ $docu->venta->VtaImpo }}</td>
        <td>{{ $docu->venta->cliente->PCNomb }}</td>
      </tr>
      @endforeach
    @endforeach
  </table>

</div>



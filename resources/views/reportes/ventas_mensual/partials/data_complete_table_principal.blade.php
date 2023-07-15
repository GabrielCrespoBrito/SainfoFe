
<table class="table">

<thead>
  <tr>
    <td></td>
    <td class="strong text-center border-width-1 border-style-right-solid" colspan="2">Total Emitido</td>
    <td class="strong text-center border-width-1 border-style-right-solid" colspan="2">Aceptados</td>
    <td class="strong text-center border-width-1 border-style-right-solid" colspan="2">Anulados</td>
    <td class="strong text-center border-width-1 border-style-right-solid" colspan="2">Rechazados</td>
    <td class="strong text-center border-width-1 border-style-right-solid" colspan="2">Pendientes</td>
  </tr>

  {{-- @dd($data['docs']) --}}
  @foreach( $data['docs'] as $docCodi => $documento )
  @if($docCodi == 52 || $docCodi == "total")
    @continue
  @endif
  <tr>
    <td class="strong"> {{ $docCodi }} {{ nombreDocumento($docCodi) }}  </td>
    
    <td class=""> <a href="#" class="btn btn-xs btn-default btn-flat"> {{ $documento['total'] }} </a> </td>
    <td class=""> {{ $documento['total'] }} </td>

    <td class=""> <a href="#" class="btn btn-xs btn-default btn-flat"> {{ $documento['total'] }} </a> </td>
    <td class="" style="padding-left:10px;"> {{ $documento['total'] }} </td>

        <td class=""> <a href="#" class="btn btn-xs btn-default btn-flat"> {{ $documento['total'] }} </a> </td>
    <td class=""> {{ $documento['total'] }} </td>

        <td class=""> <a href="#" class="btn btn-xs btn-default btn-flat"> {{ $documento['total'] }} </a> </td>
    <td class=""> {{ $documento['total'] }} </td>

        <td class=""> <a href="#" class="btn btn-xs btn-default btn-flat"> {{ $documento['total'] }} </a> </td>
    <td class=""> {{ $documento['total'] }} </td>


  </tr>
  @endforeach

</thead>

</table>
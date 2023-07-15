<table width="100%" class="table-items oneline" border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr class="header">
      <td class="text-center"> Documento </td>
      <td class="text-center"> Message </td>
      <td class="text-center"> Acción </td>
    </tr>
  </thead>

  {{-- Tbody --}}

  <tbody>

    @foreach( $docs as $doc )
    <tr>
      <td class="text-center"> <a href="{{ $doc['showRoute'] }}" target="_blank"> {{ $doc['document'] }}  </a> </td>
      <td class="text-center"> {{ $doc['message'] }} </td>
      <td class="text-center"> <a class="btn btn-xs btn-flat btn-block btn-default" href="{{ $doc['actionRoute'] }}" target="_blank"> {{ $doc['actionMessage'] }}  </a> </td>
      
    </tr>
    @endforeach


  </tbody>
</table>
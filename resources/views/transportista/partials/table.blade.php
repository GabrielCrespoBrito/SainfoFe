@component('components.table', [ 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ 'Codigo' , 'Documento' , 'Nombre Completo', 'Direccion', 'Telefono', 'Licencia', ''] ])
  @slot('body')
    @foreach ($transportistas as $transportista)
        @php
          $links = [
            ['src' => route('transportista.edit', $transportista->id) , 'texto' => 'Edit'],
            ['src' => '#' , 'texto' => 'Borrar' , 'class' => 'eliminate-element' , 'id' => $transportista->id ]
          ];
        @endphp
        <tr>
          <td> {{ $transportista->TraCodi }} </td>
          <td> {{ $transportista->getDocumentoNameComplete() }} </td>
          <td> {{ $transportista->getFullName() }} </td>
          <td> {{ $transportista->TraDire }} </td>
          <td> {{ $transportista->TraTele }} </td>
          <td> {{ $transportista->TraLice }} </td>
          <td> @include('partials.column_accion', [ 'links' => $links ]) </td>
        </tr>
    @endforeach
  @endslot  
@endcomponent
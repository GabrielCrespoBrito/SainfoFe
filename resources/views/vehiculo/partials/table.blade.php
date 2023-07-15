@component('components.table', [ 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ 'Codigo' , 'Placa' , 'Marca', 'Inscripción', ''] ])
  @slot('body')
    @foreach ($vehiculos as $vehiculo)
        @php
          $links = [
            ['src' => route('vehiculo.edit', $vehiculo->VehCodi) , 'texto' => 'Edit'],
            ['src' => '#' , 'texto' => 'Borrar' , 'class' => 'eliminate-element' , 'id' => $vehiculo->VehCodi ]
          ];
        @endphp
        <tr>
          <td> {{ $vehiculo->VehCodi }} </td>
          <td> {{ $vehiculo->VehPlac }} </td>
          <td> {{ $vehiculo->VehMarc }} </td>
          <td> {{ $vehiculo->VehInsc }} </td>
          <td> @include('partials.column_accion', [ 'links' => $links ]) </td>
        </tr>
    @endforeach
  @endslot  
@endcomponent

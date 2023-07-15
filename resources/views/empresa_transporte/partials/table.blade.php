@component('components.table', [ 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ 'Codigo' , 'RUC' , 'Nombre', 'MTC', ''] ])
  @slot('body')
    @foreach ($empresas_transporte as $empresa_transporte)
        @php
          $links = [
            ['src' => route('empresa_transporte.edit', $empresa_transporte->EmpCodi) , 'texto' => 'Edit'],
            ['src' => '#' , 'texto' => 'Borrar' , 'class' => 'eliminate-element' , 'id' => $empresa_transporte->EmpCodi ]
          ];
        @endphp
        <tr>
          <td> {{ $empresa_transporte->EmpCodi }} </td>
          <td> {{ $empresa_transporte->EmpRucc }} </td>
          <td> {{ $empresa_transporte->EmpNomb }} </td>
          <td> {{ $empresa_transporte->mtc }} </td>
          <td> @include('partials.column_accion', [ 'links' => $links ]) </td>
        </tr>
    @endforeach
  @endslot  
@endcomponent
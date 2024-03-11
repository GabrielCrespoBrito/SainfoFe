<table width="100%" style="" class="table_items">

  <thead>
    <tr>
      <th class="text-left"># </th>
      <th style="padding-left:20px" class="text-left"> NOMBRE </th>

      <th class="text-left"> T.D </th>
      <th class="text-left" style="padding-left:20px"> DOCUMENTO </th>
      <th class="text-left" style="padding-left:20px"> DIRECCION </th>
      <th class="text-left" style="padding-left:20px"> UBIGEO </th>
      <th class="text-left" style="padding-left:20px"> TELEFONO </th>
      <th class="text-right" style="padding-right:5px"> CORREO </th>
    </tr>
  </thead>

  <tbody>
    @foreach( $entidades as $entidad )
    <tr>
      <td class="text-left"> {{ $loop->index+1 }} </strong> </td>
      <td style="padding-left:20px" class="text-left"> {{ $entidad->nombre }} </strong> </td>
      @php
      $tipo_documento_nombre = App\TipoDocumento::getNombreReporte($entidad->tipo_documento);
      @endphp
      <td class="text-left"> {{ $tipo_documento_nombre }} </strong> </td>
      <td class="text-left" style="padding-left:20px">{{ $entidad->documento }}</td>
      <td class="text-left" style="padding-left:20px">{{ $entidad->direccion }}</td>
      <td class="text-left" style="padding-left:20px">{{ ubigeoNombre($entidad->ubigeo) }}</td>
      <td class="text-left" style="padding-left:20px">{{ $entidad->telefono }}</td>
      <td class="text-right" style="padding-right:5px">{{ $entidad->correo }}</td>
    </tr>
    @endforeach
  </tbody>

</table>


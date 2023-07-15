  <div class="row result-process hide">
    <p class="title"> Resultados de la busqueda </p>
    @component('components.table', ['id' => 'table-result' , 'thead' => ['Codigo', 'Descripcion' , 'Cantidad' ]])
      @slot('body')
        @foreach( $status_codes as $code )
          <tr  class="no-result" data-code="{{ $code->status_code  }}">
            <td class="status-code">
              <span class="status status-code-{{ $code->status_code }}"> {{ $code->status_code }} </span>
            </td>
            <td> {{ $code->status_message }}   </td>
            <td class="status-value"> <span class="value">0</span> </td>
          </tr>
        @endforeach
      @endslot
      @slot('tfoot')
        <tr>
          <td>Total: </td>
          <td></td>
          <td class="td-total"><span class="total"></span> </td>
        </tr>
      @endslot

    @endcomponent
  </div>
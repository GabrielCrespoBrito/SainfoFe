@component('components.modal', ['id' => 'modalImportacion', 'title' => 'Importar de Orden de  Compra' ])

  @slot('body') 

      <div class="botones_div">
        <a class="btn pull-left btn-default btn-flat aceptar_importacion">
          <span class="fa fa-download"> </span> Importar </a>
      </div> 

      <div class="factura_select">

        <table 
        class="table table-collapse table-responsive table-bordered sainfo-table" 
        id="datatable-cotizacion_select"
        data-url="{{ route('coti.search') }}"
        style="width: 100%">
        <thead>
          <tr>
            <td> Código </td>
            <td> Fecha emisión </td>
            <td> Proveedor </td>    
            <td> Importe </td>
            <td> Usuario </td>              
          </tr>
        </thead>
        <tbody></tbody>
        </table>

      </div>
  @endslot

@endcomponent
import { React, Component } from 'react';


class TableClientes extends Component {
  
  render(){
    return (
      <div>
        <div className="row">
        </div>
      </div>
    )
  }
}

export default TableClientes;

/*

<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">
  <table data-url="{{ route('admin.documentos.search')  }}" style="width: 100% !important;" class="table sainfo-table sainfo-noicon oneline" id="datatable">
    <thead>
      <tr>
        <td class="nro_venta"> N° Venta </td>
        <td class="td"> T.D </td>
        <td class="doc"> N° Doc </td>
        <td class="fecha"> Fecha </td>
        <td class="clien3"> Cliente </td>
        <td class="Moneda"> Mon </td>
        <td class="Importe"> Importe </td>
        <td class="Pago"> Pago </td>
        <td class="Saldo"> Saldo </td>
        <td class="Guia"> Est. Almacen </td>
        <td class="Estado text-center"> Estado </td>
        <td class="acciones"> &nbsp </td>
      </tr>
    </thead>
  </table>
</div>


*/
import React from 'react';
import ReactDOM from 'react-dom';
import InputsProductId from './InputsProductId';
import FieldFilterDate from './FieldFilterDate';
import ChangeFilters from './ChangeFilters ';
import FieldProductSearch from './FieldProductSearch';
import FieldAlmacenTipoReporte from './FieldAlmacenTipoReporte';
import FieldsetForm from '../FieldsetForm';
import LabelCheckbox from '../LabelCheckBox';
import CsrfFieldtoken from '../CsrfFieldtoken';

class FormKardexFisico extends React.Component {
  state = {
    searchDateByDates: true,
    searchProductById: true,
  }

  handleSubmit = (event) => {

    let form = event.target;
    let codigo_id_desde = form.querySelector('[name=articulo_desde]').value;
    let codigo_id_hasta = form.querySelector('[name=articulo_hasta]').value;

    if (this.state.searchProductById) {
      if (codigo_id_desde == '' || codigo_id_hasta == '') {
        notificaciones('Escriba El Codigo del Producto', 'error');
        event.preventDefault();
        return false;
      }
    }
  }

  // COMENTARIO@@@@

  handelChangeFilter = (event) => {
    const filter = event.target
    let stateFilterName = filter.dataset['type'] == 'date' ? 'searchDateByDates' : 'searchProductById';
    let stateFilterValue = Boolean(Number(filter.value));
    let state = {};
    state[stateFilterName] = stateFilterValue;

    this.setState(state)
  }

  render() {
    return (
      <div>
        <form
          onSubmit={this.handleSubmit}
          className="formReporte"
          action="/reportes/kardex_pdf"
          method="post">

          <CsrfFieldtoken></CsrfFieldtoken>

          <ChangeFilters
            filterDate={this.state.searchDateByDates}
            filterProduct={this.state.searchProductById}
            handelChangeFilter={this.handelChangeFilter}>
          </ChangeFilters>

          <FieldFilterDate
            filterByDate={this.state.searchDateByDates}>
          </FieldFilterDate>

          <FieldProductSearch
            filterProduct={this.state.searchProductById}>
          </FieldProductSearch>

          <FieldAlmacenTipoReporte
            filterProduct={this.state.searchProductById}>
          </FieldAlmacenTipoReporte>

          <FieldsetForm>
            <div className='col-md-12'>
              <LabelCheckbox
                name="articulo_movimiento"
                value={true}
                checked={true}
                text='Solo articulos con movimientos'
              >
              </LabelCheckbox>
            </div>
          </FieldsetForm>

          <button
            type="submit"
            defaultValue="enviar"
            className='btn btn-primary btn-flat'>Buscar
          </button>

        </form>
      </div>
    );
  }
}

if (document.getElementById('root-kardex-fisico')) {
  ReactDOM.render(<FormKardexFisico></FormKardexFisico>, document.getElementById('root-kardex-fisico'));
}


// 
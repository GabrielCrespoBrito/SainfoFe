import React from 'react';
import ReactDOM from 'react-dom';
import FieldProductSearch from '../FormKardexFisico/FieldProductSearch';
import FieldAlmacenTipoReporte from '../FormKardexFisico/FieldAlmacenTipoReporte';
import FieldsetForm from '../FieldsetForm';
import LabelCheckbox from '../LabelCheckBox';
import CsrfFieldtoken from '../CsrfFieldtoken';
import SelectsProductCategory from '../FormKardexFisico/SelectsProductCategory';
import GrupoSelect from '../GrupoSelect';
import MarcaSelect from '../MarcaSelect';

// import SelectsProductCategory form
// SelectsProductCategory

const FormProductoStock = () => {

    return (
      <div>
        <form
          className="formReporte"
          action="/reportes/productos-stock/report"
          method="post">
          <CsrfFieldtoken></CsrfFieldtoken>
          <FieldsetForm title='Filtrar Productos por Categorias y Marca'>
            <GrupoSelect allSelection={true} className="col-md-8" classNameParentSelect="col-md-6 pl-0" ></GrupoSelect>
            <div className='col-md-4'> 
              <MarcaSelect className="form-control text-center" allSelection={true}></MarcaSelect>
            </div>
          </FieldsetForm> 

          <FieldAlmacenTipoReporte/>

          <FieldsetForm>
            <div className='col-md-6'>
            <button
              type="submit"
              defaultValue="enviar"
              className='btn btn-primary btn-flat'>Buscar
            </button>
            </div>
            <div className='col-md-6'>
              <LabelCheckbox
                name="con_stock_minimo"
                value={1}
                checked={true}
                text='Con Manejo de Stock Minimo'
              >
              </LabelCheckbox>
            </div>
          </FieldsetForm>


        </form>
      </div>
    );
}

if (document.getElementById('root-producto-stock')) {
  ReactDOM.render(<FormProductoStock></FormProductoStock>, document.getElementById('root-producto-stock'));
}
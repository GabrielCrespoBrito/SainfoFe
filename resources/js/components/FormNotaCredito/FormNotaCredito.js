import React from 'react'
import ReactDOM from 'react-dom'
import Select from '../Select';
import Helper from '../../Helper';
import VentaItems from './VentaItems';
import CuotasVenta from './CuotasVenta';
import Validation from './ValidationFormNotaCredito';
import FormNotaCreditoContext from './FormNotaCreditoContext';
import FetchForm from './FetchForm';

const Descripcion = props => {
  return (
    <div className='row'>
      <div className='form-group col-md-12'>
        <input
          className='form-control text-center'
          readOnly={true}
          defaultValue={props.nombre}
        />
      </div>
    </div>
  );
}

class TipoNota extends React.Component {
  render() {
    return (
      <FormNotaCreditoContext.Consumer>
        {({ changeTipo, tipoNotaRef }) => (
          <div className='row'>
            <div className='form-group col-md-12'>
              <Select
                name="tipo"
                selectedValue={this.props.selected}
                className='form-control text-center'
                onChange={changeTipo}
                ref_={tipoNotaRef}
                options={this.props.tipos}>
              </Select>
            </div>
          </div>
        )}
      </FormNotaCreditoContext.Consumer>
    );
  }
}

const Concepto = props => {
  return (
    <div className='row mb-x10'>
      <div className="col-md-12">
        <div className="input-group ">
          <span className="input-group-addon">Concepto</span>
          <input
            type="text"
            name="concepto"
            ref={props.ref_}
            required={true}
            className="form-control text-center text-uppercase"
          />
        </div>
      </div>
    </div>
  )
}

const AfectacionIGV = props => {
  return (
    <div className='row mb-x10'>
      <div className="col-md-12">
        <div className="input-group ">
          <span className="input-group-addon">Tipo Afectaciòn de IGV</span>
          <Select
            name="tipo_igv"
            className='form-control'
            options={props.tiposIgvs}
            ref_={props.ref_}
            required={true}
            fieldsName={{ id: 'value', text: 'text' }}>
          </Select>
        </div>
      </div>
    </div>
  );
}

class InputImporte extends React.Component {

  render() {
    return (
      <div className='row mb-x10'>
        <div key={1} className="col-md-12">
          <div key={2} className="input-group ">
            <span key="nombre" className="input-group-addon">Importe</span>
            <input
              className="form-control text-center"
              readOnly={this.props.readOnly}
              required={true}
              min="0"
              type="text"
              ref={this.props.ref_}
              max={this.props.value}
              defaultValue={this.props.value}
            />
            <span key="abbre" className="input-group-addon">{this.props.monedaAbbre}</span>
          </div>
        </div>
      </div>
    );
  }
}

class Motivo extends React.Component {

  render() {
    return (
      <div className='row mb-x10'>
        <div className="col-md-12">
          <div className="input-group ">
            <span className="input-group-addon">Motivo *</span>
            <input
              type="text"
              ref={this.props.ref_}
              required={true}
              defaultValue={this.props.value}
              className="form-control text-center" />
          </div>
        </div>
      </div>
    );

  }
}

class SerieAndDate extends React.Component {

  render() {
    return (
      <div className='row'>
        <div className='form-group col-md-6'>
          <Select
            name="serie"
            className='form-control'
            fieldsName={{ id: 'id', text: 'text' }}
            ref_={this.props.serieRef}
            options={this.props.series}>
          </Select>
        </div>
        <div className='form-group col-md-6'>
          <input
            className='form-control text-center'
            type="date"
            ref={this.props.fechaRef}
            defaultValue={Helper.GetToday()}
          />
        </div>
      </div>
    );
  }
}


class FormNotaCredito extends React.Component {

  state = {
    id: null,
    url_store: null,
    search: false,
    defaultTipo: 1,
    tipo: 1,
    afectacionIGV: null,
    concepto: null,
    motivo: null,
    info: {},
  }

  tipoNotaRef = React.createRef();
  fechaRef = React.createRef();
  serieRef = React.createRef();
  motivoRef = React.createRef();
  importeRef = React.createRef();
  conceptoRef = React.createRef();
  tipoIgvRef = React.createRef();
  cuotasRef = React.createRef();

  fetchInfo(url, url_store) {

    fetch(url)
      .then(res => res.json())
      .then(res => {
        res.items = res.items.map((item, index) => {
          item.max = item.cantidad;
          return item;
        })
        this.setState({
          search: true,
          info: res,
          tipo: this.state.defaultTipo,
          url_store: url_store,
        })
      })
      .catch(res => console.log(res))
  }

  changeTipo = (event) => {
    this.setState({
      tipo: event.target.value,
    })
  }

  handleChangeQty = (event) => {

    const value = Number(event.target.value);
    const id = event.target.dataset['key'];
    let info = { ...this.state.info };

    console.log(event);

    if (value === 0) {
      console.log("La Cantidad tiene que ser mayor a 0");
      notificaciones("La Cantidad tiene que ser mayor a 0", 'error');
      event.nativeEvent.preventDefault();
      return;
    }

    info.items = info.items.map((item, index) => {

      if (item.id == id) {
        item.cantidad = value;
      }

      return item;
    });


    this.setState({
      info
    })
  }

  handleDeleteItem = (event) => {

    const id = event.target.dataset['key']
    const info = { ...this.state.info }

    if (info.items.length == 1) {
      notificaciones('Tiene que tener al menos 1 item', 'warning');
      return;
    }

    info.items = info.items.filter((item, index) => {
      return !(item.id == id);
    });

    this.setState({
      info
    })

  }

  handleSubmit = (event) => {

    event.preventDefault();
    const validator = new Validation(this);

    if (!validator.validate()) {
      notificaciones(validator.getErrors(), 'error');
      return false;
    }

    const data = validator.getDataForm()

    FetchForm(this.state.url_store, data, () => {
      window.table_ventas.draw();
    })

    return false;
  }

  getFields() {

    const { tipo, info: { items, monedaAbbre, monto, tiposIgvs } } = this.state;

    if (tipo == 1) {
      return [

        <InputImporte
          value={monto}
          ref_={this.importeRef}
          readOnly={true}
          monedaAbbre={monedaAbbre} />,

        <Motivo
          ref_={this.motivoRef}
          value={this.state.motivo}
        />
      ]
    }

    if (tipo == 2) {
      return [
        <Motivo
          ref_={this.motivoRef}
          value={this.state.motivo}
        />,
        <VentaItems items={items} />
      ]
    }

    if (tipo == 3) {
      return [
        <Concepto ref_={this.conceptoRef} />,
        <AfectacionIGV
          tiposIgvs={tiposIgvs}
          ref_={this.tipoIgvRef}
        />,
        <InputImporte
          ref_={this.importeRef}
          monedaAbbre={monedaAbbre}
        />
      ]
    }

    if (tipo == 4) {

      return [

        <Motivo
          ref_={this.motivoRef}
          value={this.state.motivo}
        />,

        <InputImporte
          value={monto}
          ref_={this.importeRef}
          readOnly={true}
          monedaAbbre={monedaAbbre}
        />,

        <CuotasVenta ref={this.cuotasRef} />
      ]
    }


  }

  closeModal = (event) => {
    event.preventDefault();
    window.$("#modalNC").modal('hide');
  }

  render() {

    if (!this.state.search) {
      return null;
    }

    const TIPOS = [
      { value: 1, text: 'Nota de crèdito Total' },
      { value: 2, text: 'Nota de crèdito Parcial' },
      { value: 3, text: 'Nota de crèdito Otro Concepto' },
      { value: 4, text: 'Ajuste de montos y/o fechas de pago' },
    ];

    const { series, url_store, correlative, } = this.state.info;

    return (
      <FormNotaCreditoContext.Provider
        value={{
          changeTipo: this.changeTipo,
          handleChangeQty: this.handleChangeQty,
          handleDeleteItem: this.handleDeleteItem,
          tipoNotaRef: this.tipoNotaRef
        }}>
        <div>
          <form
            onSubmit={this.handleSubmit}
            action={url_store}
            method="post"
          >

            <Descripcion nombre={correlative} />

            <TipoNota
              tipos={TIPOS}
              selected={this.state.tipo}
            />

            <SerieAndDate
              fechaRef={this.fechaRef}
              serieRef={this.serieRef}
              series={series}
            />

            {this.getFields()}

            <button
              type='submit'
              className='btn btn-flat btn-primary'> Guardar
            </button>

            <a
              href="#"
              onClick={this.closeModal}
              className='btn btn-flat btn-default'> Salir
            </a>

          </form>
        </div>
      </FormNotaCreditoContext.Provider>
    )
  }
}

if (document.getElementById('form-nc')) {
  window.form_nota_credito = ReactDOM.render(<FormNotaCredito></FormNotaCredito>, document.getElementById('form-nc'));
}
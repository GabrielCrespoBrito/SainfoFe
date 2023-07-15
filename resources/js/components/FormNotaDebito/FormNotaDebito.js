import React, { useEffect, useState } from 'react'
import ReactDOM from 'react-dom'
import Form from '../Form';
import Enlace from '../Enlace';
import Helper from '../../Helper';
import Button from '../Button';
import Select from '../Select';
import Validation from './ValidationFormNotaDebito';
import FetchForm from '../FormNotaCredito/FetchForm';

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
            defaultValue=''
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
        <div className="col-md-12">
          <div className="input-group ">
            <span key="nombre" className="input-group-addon">Importe</span>
            <input
              className="form-control text-center"
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



class FormNotaDebito extends React.Component {

  state = {
    id: null,
    url_store: null,
    search: false,
    info: {},
  }

  fechaRef = React.createRef();
  serieRef = React.createRef();
  importeRef = React.createRef();
  conceptoRef = React.createRef();
  tipoIgvRef = React.createRef();

  componentDidUpdate(prevProps, prevState) {
    if (prevState.url_store !== this.state.url_store) {
      this.conceptoRef.current.value = '';
      this.importeRef.current.value = this.state.info.monto;
      this.fechaRef.current.value = Helper.GetToday();
      this.tipoIgvRef.current.options[0].selected = true;
    }
  }

  fetchInfo(url, url_store) {
    fetch(url)
      .then(res => res.json())
      .then(res => {
        this.setState({
          search: true,
          info: res,
          url_store: url_store,
        })
      })
      .catch(res => console.log(res))
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
    });
    return false;
  }

  closeModal = (event) => {
    event.preventDefault();
    window.$("#modalND").modal('hide');
  }

  render() {

    if (this.state.search == false) {
      return null;
    }

    const { url_store, monto, series, tiposIgvs, monedaAbbre, correlative } = this.state.info;

    return (
      <Form
        action={url_store}
        method="post"
        handleSubmit={this.handleSubmit}>

        <Descripcion nombre={correlative} />

        <SerieAndDate
          fechaRef={this.fechaRef}
          serieRef={this.serieRef}
          series={series}
        />

        <Concepto ref_={this.conceptoRef} />

        <AfectacionIGV
          tiposIgvs={tiposIgvs}
          ref_={this.tipoIgvRef}
        />

        <InputImporte
          value={monto}
          ref_={this.importeRef}
          monedaAbbre={monedaAbbre}
        />

        <Button
          type="submit"
          className="btn btn-flat btn-primary"> Guardar
        </Button>

        <Enlace
          href="#"
          onClick={this.closeModal}
          className="btn btn-flat btn-default pull-right"> Salir
        </Enlace>


      </Form>
    )
  }
}

if (document.getElementById('form-nd')) {
  window.form_nota_debito = ReactDOM.render(<FormNotaDebito></FormNotaDebito>, document.getElementById('form-nd'));
}
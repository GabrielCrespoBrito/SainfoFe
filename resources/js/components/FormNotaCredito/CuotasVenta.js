import { React, Component } from 'react'
import Helper from '../../Helper';

const Cuota = props => {
  const { cuota, handleChangeMonto, handleChangeDate, handleDeleteCuota } = props;

  return (
    <tr key={cuota.id} className=''>
      <td key={1}>
        <input
          type="date"
          data-key={cuota.id}
          className='form-control text-center input-sm'
          required={true}
          onChange={handleChangeDate}
          defaultValue={cuota.fecha}
        />
      </td>
      <td key={2}>
        <input
          type="number"
          data-key={cuota.id}
          defaultValue={cuota.monto}
          required={true}
          onChange={handleChangeMonto}
          className='form-control text-right input-sm'
        />
      </td>
      <td key={3}>
        <a
          href="#"
          data-key={cuota.id}
          onClick={handleDeleteCuota}
          className='btn btn-xs btn-default pull-right color-red'>
          <span data-key={cuota.id} className='fa fa-minus'></span>
        </a>
      </td>
    </tr>
  )
}

class CuotasVenta extends Component {

  state = {
    cuotas: []
  }

  newDataCuota() {
    return {
      id: Math.random().toString(),
      fecha: Helper.GetToday(),
      monto: null
    }
  }

  addCuota = () => {
    this.setState({
      cuotas: [...this.state.cuotas, this.newDataCuota()]
    });
  }


  handleChangeMonto = (event) => {

    let id = event.target.dataset['key'];
    let value = event.target.value;
    const cuotas = this.state.cuotas.map(cuota => {
      if (cuota.id == id) {
        cuota.monto = value
      }
      return cuota;
    });

    this.setState({
      cuotas: cuotas
    })    
  }

  handleChangeDate = (event) => {

    let id = event.target.dataset['key'];
    let value = event.target.value;
    const cuotas = this.state.cuotas.map(cuota => {
      if(cuota.id == id){
        cuota.fecha = value
      }
      return cuota;      
    });

    this.setState({
      cuotas: cuotas
    })
  }

  handleDeleteCuota = (event) => {
    event.preventDefalt;
    let id = event.target.dataset['key']

    console.log(this.state.cuotas);
    const cuotas = this.state.cuotas.filter( cuota => cuota.id != id );

    this.setState({
      cuotas : cuotas
    })
  }


  render() {
    const { cuotas } = this.state;

    return (
      <div className="items">
        <button
          type="button"
          onClick={this.addCuota}
          className='btn btn-flat btn-succes btn-block mb-x10'>
          <span className='fa fa-plus'></span> Agregar Cuota
        </button>

        {Boolean(cuotas.length) &&
          (<table className="table table-responsive table-compact mb-x10">
            <thead>
              <tr className="strong">
                <td width="45%" className='pb-x5 pt-x5 text-center'>Fecha Cuota</td>
                <td width="45%" className='pb-x5 pt-x5 text-center'>Monto</td>
                <td width="10%"></td>
              </tr>
            </thead>
            <tbody>
              {cuotas.map((cuota, index) => (
                <Cuota
                  cuota={cuota}
                  handleChangeMonto={this.handleChangeMonto}
                  handleChangeDate={this.handleChangeDate}
                  handleDeleteCuota={this.handleDeleteCuota}
                ></Cuota>
              ))}
            </tbody>
          </table>
          )}
      </div>
    )
  }
}

export default CuotasVenta;
import React from 'react';
import FieldsetForm from '../FieldsetForm';
import MesSelect from '../MesSelect';
import InputsDate from './InputsDate';



class FieldFilterDate extends React.Component {

  state = {
    meses : [],
  }

  setMeses = (meses) => this.setState({ meses })

  render(){

  const { filterByDate } = this.props;

  const fechas = document.getElementById("root-kardex-fisico").dataset;
    console.log(fechas);



  return (
    <FieldsetForm title={filterByDate ? 'Filtrar por Fechas' : 'Filtrar Por Mes'} >
      {filterByDate ? 
        <InputsDate className='col-md-12' inicio={fechas.date_init} final={fechas.date_end}  /> : 
        <MesSelect setMeses={this.setMeses} 
        meses={this.state.meses} 
        className='col-md-12' />
      }      
    </FieldsetForm>
  );

  // return (
  //   <FieldsetForm title={filterByDate ? 'Filtrar por Fechas' : 'Filtrar Por Mes'} >      
  //     <InputsDate className={filterByDate ? 'col-md-12 show' : 'col-md-12  hide'} />
  //     <MesSelect className={filterByDate ? 'col-md-12  hide' : 'col-md-12  show'} />
  //   </FieldsetForm>
  // );

  }
}



export default FieldFilterDate;

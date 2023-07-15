import React, { useEffect, useState } from 'react';
import Select from './Select';

const URL = "/searchMarcas";

const MarcaSelect = props => 
{
  const [ marcas , setMarcas ] = useState([]);
  
  useEffect(() => {
    fetch(URL)
      .then(res => res.json())
      .then(res => {
        if (props.allSelection) {
          const optionAll = { MarCodi: "todos", MarNomb: "-- TODOS --" }
          res.unshift(optionAll);
        }
        setMarcas(res)
      })
      .catch(error => console.log("error response", error))
  }, [])

  return (
    <Select 
      name="MarCodi"
      className={props.className} 
      fieldsName={{ 'id': 'MarCodi', 'text': 'MarNomb' }} 
      options={marcas}
    />
  );
}

MarcaSelect.defaultProps = {
  'allSelection' : false,
  'className' : 'form-control'
}

export default MarcaSelect;
import React from 'react';

function SelectTipoReporte (props) {
    return (
      <div className={props.className}>
        <select 
        required 
        name="tipo_reporte" 
        className="form-control input-sm flat text-center">
          {
            props.tipos.map(tipo =>
              <option
                key={tipo.id}
                value={tipo.id}>
                {tipo.text}
              </option>
            )
          }
        </select>
      </div>
    );
}

SelectTipoReporte.defaultProps = {
  className: 'col-md-12',
  tipos : [
    {  
      id : 'pdf' , 
      text  : 'PDF'
    },
    {
      id: 'excell',
      text: 'Excell'
    },    
  ]
}



export default SelectTipoReporte;
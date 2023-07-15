import React from 'react';

const InputsDate = (props) => {
  return (
    <div className={props.className}>
      <div className="col-md-6">
        <input 
        required 
        type="date" 
        name="fecha_inicio"
        defaultValue={props.inicio}
        className="form-control input-sm flat text-center" />
      </div>

      <div className="col-md-6">
        <input 
        required 
        type="date" 
        name="fecha_fin"
          defaultValue={props.final}
        className="form-control input-sm no_br flat text-center" />
      </div>
    </div>
  );
}
export default InputsDate
import React from 'react';

export default function InputsProductId (props) {
  return (    
    <div className={props.className}>
      <div className="col-md-6">
        <select name="articulo_desde" className="form-control input-sm flat text-center">
        </select>
      </div>
      <div className="col-md-6">
        <select name="articulo_hasta" className="form-control input-sm  flat text-center">
        </select>
      </div>
    </div>
  );
}
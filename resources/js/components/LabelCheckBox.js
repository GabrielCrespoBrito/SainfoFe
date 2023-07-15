import React from 'react';

export default function LabelCheckbox(props) {

  return (
    <label htmlFor={props.htmlFor}>
      <input 
        id={props.htmlFor} 
        type="checkbox" 
        name={props.name}
        defaultValue={props.value} 
        defaultChecked={props.checked} /> 
          {props.text}
    </label>
  );
}



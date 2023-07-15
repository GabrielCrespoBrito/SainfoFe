import React from 'react';

const Form = (props) =>  {  
  return (
    <form
      onSubmit={props.handleSubmit}
      action={props.action}
      method={props.method}
    >
    {props.children}
    </form>
  );
}

Form.defaultProps = {
  method: 'post',
  handleSubmit: () => true,
}

export default Form;
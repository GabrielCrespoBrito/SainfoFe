import React from 'react';

class Input extends React.Component {

  static defaultProps = {
    className: 'form-control',
    onChange: event => null,
    onClick: event => null,
    readOnly : false,
  }

  render() {
    const { className, onChange, onClick, value, readOnly, name, ref } = this.props;
    return (
      <input
        name={name}
        className={className}
        onChange={onChange}
        onClick={onClick}
        defaultValue={value}
        readOnly={readOnly}
        ref={ref}
      />
    );
  }
}

export default Input;
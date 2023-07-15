import React from 'react';

class Select extends React.Component {

  static defaultProps = {
    className: 'col-md-12',
    fieldsName: { id: 'value', text: 'text' },
    selectedValue : 'null',
    onChange: event => null,
  }

  render() {
    const { fieldsName, options, selectedValue, className, onChange, name, ref_ } = this.props;
    return (
      <select
        name={name}
        className={className}
        onChange={onChange}
        value={selectedValue}
        ref={ref_}
      >
        {options.map((option, index) => {
          return (
            <option
              key={option[fieldsName.id]}
              value={option[fieldsName.id]}
            >
              {option[fieldsName.text]}
            </option>
          )
        })}
      </select>
    );
  }
}

export default Select;
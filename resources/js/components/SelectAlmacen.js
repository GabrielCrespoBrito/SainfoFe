import React from 'react';

const URL = "/searchAlmacenes";

class SelectAlmacen extends React.Component {

  state = {
    almacenes: [],
  }
  
  static defaultProps = {
    className: 'col-md-12',
    optionEmpty : true
  }

  componentDidMount() {
    fetch(URL)
      .then(res => res.json())
      .then(res => {
        this.props.optionEmpty ? res.unshift({ LocCodi: 'todos', LocNomb: '-- TODOS --' }) : res
        this.setState({ almacenes : res })
      })
      .catch(error => console.log("error response", error))
  }

  render() {
    return (
      <div className={this.props.className}>
        <select 
          required 
          name="LocCodi"
          className="form-control input-sm flat text-center">
          {
            this.state.almacenes.map(almacen =>
              <option
                key={almacen.LocCodi}
                value={almacen.LocCodi}>
                {almacen.LocNomb}
              </option>
            )
          }
        </select>
      </div>
    );
  }
}

export default SelectAlmacen;
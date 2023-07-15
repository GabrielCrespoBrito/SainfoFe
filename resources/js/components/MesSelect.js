import React from 'react';


const URL = "/searchMeses";

class MesSelect extends React.Component {

  state = {
    meses: this.props.meses
  }

  static defaultProps = {
    className: 'col-md-12'
  }

  componentDidMount() {

    if(this.state.meses.length){
      return true;
    }

    fetch(URL)
      .then(res => res.json())
      .then(res => {
        const meses = res.data.reverse();
        this.props.setMeses(meses)
        this.setState({ meses })
        }
      )
      .catch(error => console.log("error response", error))
  }

  render() {
    console.log("meses", this.props.meses)

    return (
      <div className={this.props.className}>
        <select 
          required 
          name="MesCodi" 
          className="form-control input-sm flat text-center">
          {
            this.state.meses.map((mes) => {
              return (
                <option key={mes.mescodi} value={mes.mescodi}> {mes.mesnomb} </option>
              )
            })
          }
        </select>
      </div>
    );
  }
}



export default MesSelect;
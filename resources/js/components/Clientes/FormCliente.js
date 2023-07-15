import { React, Component } from 'react';


class FormClientes extends Component {


  constructor(props){
    super(props);

    this.inputNombre = React.createRef();
    this.input = React.createRef();

  }



  componentDidMount() {
  }


  handleSubmit = (event) => {
    event.preventDefault();
    console.log("")
  }

  render() {
    return (
      <div>
        <form onSubmit={this.handleSubmit}>

          <input ref={this.entrada}>  </input>

        </form>
      </div>
    )
  }
}

export default FormClientes;
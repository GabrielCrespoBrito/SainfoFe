import { React, Component } from 'react';
import ReactDOM from 'react-dom';
import TableClientes from '../components/Clientes/TableClientes';

if (document.getElementById('root-client')) {
  ReactDOM.render(<TableClientes></TableClientes>, document.getElementById('root-client'));
}


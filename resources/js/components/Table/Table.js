import React from 'react';
import ReactDOM from 'react-dom';
import Tbody from './Tbody'
function Table() {

  return (
    <table>
      <Thead></Thead>
      <Tbody></Tbody>
    </table>
  );
}

Table.defaultProps = {
  footer : false
}

export default Table;
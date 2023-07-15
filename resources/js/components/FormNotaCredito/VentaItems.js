import { React, Component } from 'react'

import FormNotaCreditoContext from './FormNotaCreditoContext';

class VentaItems extends Component {

  state = {
    items : this.props.items
  }

  render() {
    const { items } = this.props;
    return (
      <FormNotaCreditoContext.Consumer>
        {({ handleDeleteItem, handleChangeQty }) => (
      <div className="items">
        <table className="table table-responsive table-compact">
          <thead>
            <tr className="strong"> 
              <td className='pb-x5 pt-x5'>Descripciòn</td>
              <td className='pb-x5 pt-x5'>Cantidad</td>
            </tr>
          </thead>
          <tbody>
            {items.map((item, index) => (
              <tr key={item.id} className=''>
                <td width="75%"> {item.text} </td>
                <td width="20%"> 
                  <input 
                    type="number"
                    min="0"
                    data-key={item.id}
                    max={item.max}
                    className='form-control text-right input-sm'
                    onChange={handleChangeQty}
                    defaultValue={item.cantidad} 
                    />
                </td>
                <td width="5%">
                  <button 
                    data-key={item.id} 
                    onClick={handleDeleteItem} 
                    type="button"
                    className='btn btn-xs btn-default pull-right color-red'>
                    <span data-key={item.id} className='fa fa-minus'></span>
                  </button>
                </td>                
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      )}
      </FormNotaCreditoContext.Consumer>
    )
  }
}


export default VentaItems;
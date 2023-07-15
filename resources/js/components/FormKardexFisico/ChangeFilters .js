import React from 'react';


let GetInput = (props) => {
  return  (
  <label  style={{ paddingLeft: '10px' }} htmlFor={props.forName}>
    <input
      type="radio"
      id={props.forName}
      name={props.name}
      data-type={props.type}
      defaultValue={props.value}
      defaultChecked={props.checked}
      onChange={props.handelChangeFilter}
    />
    <span className='filter-name'>{ props.text }</span>
  </label>
  );
}
export default function ChangeFilters(props) {

  const { filterDate, filterProduct, handelChangeFilter } = props;
  
  return (    
    <div className='row'>
      <div className='col-md-12'> 
        <span>Filtrar Por</span>

        <GetInput 
          forName="fechaByDate"
          name="filterDate"
          type="date" 
          checked={filterDate}
          value={1} 
          handelChangeFilter={handelChangeFilter}
          text="Fecha"  
        />

        <GetInput
          forName="fechaByMes"
          name="filterDate"
          type="date"
          checked={!filterDate}
          value={0}
          handelChangeFilter={handelChangeFilter}
          text="Mes"
        />
      </div>

      <div className='col-md-12'> 
        <span >Filtrar Producto  Por </span>    

          <GetInput
            forName="filterByCodigo"
            name="filterProducto"
            type="product"
            checked={filterProduct}
            value={1}
            handelChangeFilter={handelChangeFilter}
            text="Codigo"
          />

          <GetInput
            forName="filterByCategory"
            name="filterProducto"
            type="product"
            checked={!filterProduct}
            value={0}
            handelChangeFilter={handelChangeFilter}
            text="Categoria"
          />
      </div>
      
    </div>
  );
}
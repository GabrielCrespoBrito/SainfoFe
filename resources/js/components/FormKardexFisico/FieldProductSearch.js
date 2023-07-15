import React from 'react';
import FieldsetForm from '../FieldsetForm';
import InputsProductId from './InputsProductId';
import SelectsProductCategory from './SelectsProductCategory';

export default function FieldProductSearch(props) {

  const { filterProduct } = props;

  return (
    <FieldsetForm title={filterProduct ? 'Filtrar por Codigos de Producto' : 'Filtrar Por Categorias'} >
      <InputsProductId className={filterProduct ? 'show' : 'hide'} />
      <SelectsProductCategory className={filterProduct ? 'hide' : 'show'} />
    </FieldsetForm>
  );
}
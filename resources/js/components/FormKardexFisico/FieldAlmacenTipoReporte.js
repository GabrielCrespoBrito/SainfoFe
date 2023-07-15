import React from 'react';
import FieldsetForm from '../FieldsetForm';
import SelectAlmacen from '../SelectAlmacen';
import SelectTipoReporte from '../SelectTipoReporte';

export default function FieldAlmacenTipoReporte() {

  return (
    <FieldsetForm title='Almacen y Tipo de Reporte'>
      <SelectAlmacen className='col-md-6' />
      <SelectTipoReporte className='col-md-6' />
    </FieldsetForm>
  );
}
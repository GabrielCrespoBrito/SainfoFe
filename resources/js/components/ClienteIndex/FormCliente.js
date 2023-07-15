import { createRef, useContext, useState } from "react";
import Helper from "../../Helper";
import ClienteIndexContext from "./ClienteIndexContext";

function FormCliente(props) {

  const { client, handleSubmit, handleDelete } = useContext(ClienteIndexContext);
  const createAction = Helper.IsObjEmpty(client);
  const activeProp = createAction ? true : client.active

  const [active, setActive] = useState(activeProp);
  const btnText = createAction ? "Guardar" : 'Actualizar';

  const razonSocialRef = createRef();
  const rucRef = createRef();
  const imageRef = createRef();
  const sitioRef = createRef();
  
  const handleSubmit_ = (e) => {
    e.preventDefault();
    const image = imageRef.current.files[0] || null;
    const data = {
      razon_social: razonSocialRef.current.value,
      sitio: sitioRef.current.value,
      ruc: rucRef.current.value,
      active: Number(active),
    }
    
    if(image){
      data.image = image;
    }

    handleSubmit(data);
  };

  const onChangeStatus = (e) => {
    setActive(e.target.value)
  }

  const changeTitle = () => {

    razonSocialRef.current.value;
  }

  return (
    <>
      <form onSubmit={handleSubmit_}>
    
      <div className="form-group">
        <label  htmlFor="razonSocial">Razon Social</label>
          <input 
            required 
            type="text"
            onChange={changeTitle}
            id="razonSocial"
            className="form-control" 
            defaultValue={client.razon_social} 
            ref={razonSocialRef} 
            placeholder="Razon Social" />
      </div>

      <div className="form-group">
        <label>Ruc</label>
        <input 
          required 
          type="text"
          defaultValue={client.ruc}
          ref={rucRef}
          className="form-control" 
          placeholder="Ruc" 
        />
      </div>

        <div className="form-group">
          <label htmlFor="razonSocial">Sitio Web</label>
          <input
            type="url"
            id="sitio"
            className="form-control"
            defaultValue={client.sitio}
            ref={sitioRef}
            placeholder="Sitio Web" />
        </div>

      <div className="form-group">
        <label>Imagen</label>

        <input 
          type="file" 
          ref={imageRef}
          required={createAction}
          className="form-control"
        />

      </div>

      <div className="form-group">
        <label>Estatus</label>
      </div>

      <div className="form-group">
        <label>
          <input 
            type="radio"
            onChange={onChangeStatus}
            name="active"
            defaultValue="1"
            required
            defaultChecked={active == '1' || createAction } 
            /> Activo
        </label>
        
        <label style={{ marginLeft: '20px' }}>
          <input 
            type="radio"
            onChange={onChangeStatus}
            name="active"
            required
            defaultValue="0"
            defaultChecked={active == '0'}
            /> Inactivo
        </label>
      </div>

    <div className="row">
      <div className="col-md-12">
        <button className="btn btn-flat btn-primary">{btnText}</button>
        {!createAction && 
        <button 
          type="button"
          onClick={() => handleDelete(client.id)}
          className="pull-right btn btn-flat btn-primary"> <span className="fa fa-danger"></span> Eliminar
        </button>}
      </div>
    </div>
    </form>
    </>
  )
}

export default FormCliente;


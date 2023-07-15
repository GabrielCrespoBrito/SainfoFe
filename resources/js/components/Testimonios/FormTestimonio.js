import { createRef, useContext, useEffect, useState } from "react";
import Helper from "../../Helper";
import Select from "../Select";
import { search as searchCliente } from "../ClienteIndex/ClientRequests";
import { create, update, destroy } from "./TestimonioRequest";
import TestimonioContext from "./TestimonioContext";



function FormTestimonio({ successFunc, errorFunc, model })
{
  const { urls } = useContext(TestimonioContext);
  console.log(urls);

  const [clients, setClients] = useState([]);

  const search = () => {    
    searchCliente(urls.searchCliente).then((res) => setClients(res.data.clients));
  }

  useEffect(() => {
    search();
  }, [])

  if (clients.length == 0) {
    return null;
  }

  // const { model, actionCreate, createAction, handleDelete } = useContext(TestimonioContext);
  // const createAction = Helper.IsObjEmpty(client);
  // const activeProp = createAction ? true : null
  const createAction = Helper.IsObjEmpty(model);
  const btnText = createAction ? "Guardar" : 'Actualizar';
  const selectedValue = model.id;

  console.log("presentanten", model )

  // Ref;
  const clienteIdRef = createRef();
  const representanteRef = createRef();
  const cargoRef = createRef();
  const testimonioRef = createRef();
  const imageRef = createRef();
  const urlVideoRef = createRef();


  const handleDelete = () => 
  {
    if(confirm('Esta Seguro de eliminar este testimonio?')){      
      destroy(urls.destroy, model.id).then(res => {
        console.log("log", res);
        successFunc(res)
      })
    }
  }

  const handleSubmit = (e) => {

    e.preventDefault();

    if (testimonioRef.current.value.trim() == "" && urlVideoRef.current.value.trim() == "" ){
      Helper.Notificacion.error('Tiene que registrar el testimonio en texto o en video (url)')
      testimonioRef.current.focus();
      return false;
    }

    let bodyFormData = new FormData();
      bodyFormData.append('cliente_id', clienteIdRef.current.value );
      bodyFormData.append('representante',representanteRef.current.value );
      bodyFormData.append('cargo',cargoRef.current.value );

      if( createAction ){
        bodyFormData.append('imagen',imageRef.current.files[0]);
      }
      else {
        if (imageRef.current.files[0]){
          bodyFormData.append('imagen', imageRef.current.files[0]);
        }
      }


      bodyFormData.append('testimonio_text',testimonioRef.current.value );
      bodyFormData.append('url_video',urlVideoRef.current.value );


    if(createAction){
      create( urls.store, bodyFormData).then((res) => {
        if (res.status == 200) {
          successFunc(res);
        }
        else {
          Helper.Notificacion.error(res.request.response)
        }

      }).catch((res) => {
        console.log("catch", res)
      })
    }
    else {
      update( urls.update, model.id, bodyFormData).then((res) => {
        console.log("resUpdate",res);
        if(res.status == 200){
          successFunc(res);
        }
        else {
          Helper.Notificacion.error(res.request.response)
        }

      }).catch( res => {
        console.log("catch", res)
      })
    }
  };

  return (
    <>
      <form onSubmit={handleSubmit}>

        <div className="form-group">
          <label htmlFor="razonSocial">Clientes</label>
          <Select ref_={clienteIdRef} selectedValue={selectedValue} className="form-control" options={clients} fieldsName={{ id: 'id', text: 'razon_social' }}></Select>  
        </div>

        <div className="row">

          <div className="form-group col-md-6">
            <label htmlFor="representante">Representante</label>
            <input
              required
              type="text"
              id="representante"
              className="form-control"
              defaultValue={model.representante}
              ref={representanteRef}
              placeholder="Representante" />
          </div>

          <div className="form-group col-md-6">
            <label htmlFor="cargo">Cargo</label>
            <input
              required
              type="text"
              id="cargo"
              className="form-control"
              defaultValue={model.cargo}
              ref={cargoRef}
              placeholder="Posición del Representante" />
          </div>

        </div>

        <div className="row">

          <div className="form-group col-md-12">
            <label htmlFor="testimonio"> Testimonio </label>
            <textarea
              id="testimonio"
              type="text"
              className="form-control"
              defaultValue={model.testimonio_text}
              rows={3}
              ref={testimonioRef}>
            </textarea>
          </div>

        </div>

        <div className="form-group">
          <label htmlFor="enlace_video">Enlace Video</label>
          <input
            type="url"
            id="enlace_video"
            className="form-control"
            defaultValue={model.url_video}
            ref={urlVideoRef}
            placeholder="https://www.youtube.com/watch?v=sw7463aYB5k" />
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

        <div className="row">
          <div className="col-md-12">
            <button className="btn btn-flat btn-primary">{btnText}</button>
            {!createAction &&
              <button
                type="button"
                onClick={handleDelete}
                className="pull-right btn btn-flat btn-danger"> <span className="fa fa-trash"></span> Eliminar
              </button>}
          </div>
        </div>
      </form>
    </>
  )
}

FormTestimonio.defaultProps = {
  deleteAction: false,
  model: {},
  successFunc: () => null,
  errorFunc: () => null,
}


export default FormTestimonio;


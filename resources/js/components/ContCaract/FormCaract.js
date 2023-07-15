import { createRef, useContext, useEffect, useState } from "react";
import Helper from "../../Helper";
import Select from "../Select";
import { search as searchCliente } from "../ClienteIndex/ClientRequests";
import { create, update, destroy } from "./ContCaractRequest";
import TestimonioContext from "./ContCaractContext";



function FormCaract({ successFunc, errorFunc, model })
{
  const { urls } = useContext(TestimonioContext);
  const createAction = Helper.IsObjEmpty(model);
  const btnText = createAction ? "Guardar" : 'Actualizar';
  const selectedValue = model.id;

  console.log("presentanten", model )

  // Ref;
  const nombreRef = createRef();
  const descripcionRef = createRef();
  const iconRef = createRef();
  const iconColorRef = createRef();
  const cardColorRef = createRef();
  
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

    let bodyFormData = new FormData();
    bodyFormData.append('nombre', nombreRef.current.value);
    bodyFormData.append('descripcion', descripcionRef.current.value);
    bodyFormData.append('icon', iconRef.current.value);
    bodyFormData.append('icon_color', iconColorRef.current.value);
    bodyFormData.append('card_color', cardColorRef.current.value);

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


        <div className="row">
          <div className="form-group col-md-12">
            <label htmlFor="nombre">Nombre</label>
            <input
              required
              type="text"
              id="nombre"
              className="form-control"
              defaultValue={model.nombre}
              ref={nombreRef}
              placeholder="nombre" />
          </div>
        </div>

        <div className="row">
          <div className="form-group col-md-12">
            <label htmlFor="descripcion"> Descripcion </label>
            <textarea
              id="descripcion"
              type="text"
              required
              className="form-control"
              maxLength={255}              
              defaultValue={model.descripcion}
              rows={3}
              ref={descripcionRef}>
            </textarea>
          </div>
        </div>


        <div className="row">
          <div className="form-group col-md-12">
            <label htmlFor="icon">Icono  <a target="_blank" href="https://heroicons.com/"> <span className="fa fa-external-link"></span> </a> </label>
            <input
              required
              type="text"
              id="icon"
              className="form-control"
              defaultValue={model.icon}
              ref={iconRef}
              placeholder="Pegar Icono SVG" />
          </div>
        </div>

        <div className="row">
          <div className="form-group col-md-6">
            <label htmlFor="icon">Color Icono </label>
            <input
              required
              type="color"
              id="icon"
              className="form-control"
              defaultValue={model.icon_color || "#000000"}
              ref={iconColorRef}
              placeholder="Pegar Icono SVG" />
          </div>
          <div className="form-group col-md-6">
            <label htmlFor="icon">Caja Color </label>
            <input
              required
              type="color"
              className="form-control"
              defaultValue={model.card_color || "#309df0" }
              ref={cardColorRef}
              placeholder="Pegar Icono SVG" />
          </div>
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

FormCaract.defaultProps = {
  deleteAction: false,
  model: {},
  successFunc: () => null,
  errorFunc: () => null,
}


export default FormCaract;


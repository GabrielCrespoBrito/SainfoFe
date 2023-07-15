import React, { useRef, useState } from 'react';
import ReactDOM from 'react-dom';


const rootEle = document.getElementById('root-toma-inventario');
const loadScreen = document.getElementById("load_screen")
// loadScreen.style.display = "none"
function TomaProductoImport() {
  const [isSubmiting, setSubmiting] = useState(false);
  const [isSeaching, setSearch] = useState(false);
  const [isLoad, setIsLoad] = useState(false);
  const refFile = useRef();
  const refEstado = useRef();
  
  const showErrors = responseError =>
  {
    if(responseError.response.data){

      if (responseError.response.data.message) {
        const errorArr = responseError.response.data.message.split('|');
        notificaciones(errorArr, 'error');        
      }
      else {
        notificaciones(responseError.message);
      }
    }

    else {
      notificaciones(responseError.message);
    }
  }

  const downLoadExcell = async () => {
    setSearch(true);
    try {
      const data = await axios.post(rootEle.dataset.url);
      let content = "data:application/xlsx;base64,".concat(data.data.content);
      download(content, data.data.name, "application/xlsx");
    } catch (err) {
      setSearch(false);
      return err;
    }
    setSearch(false);
  }

  const loadFile = (e) => {
    setIsLoad(true);
  }

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!isLoad) {
      notificaciones('Tiene que cargar el archivo Excell');
      return;
    }

    setSubmiting(true);
    loadScreen.style.display = "block";
    try {
      const dataForm = new FormData();
      dataForm.append('excell', refFile.current.files[0])
      // dataForm.append('estado', refEstado.current.checked ? 'C' : 'P')
      dataForm.append('local', document.querySelector("[name=local]").value)
      const data = await axios.post(rootEle.dataset.urlsend, dataForm);
      notificaciones(data.data.message,'success');
      $("#modalImport").modal('hide');
      setTimeout(() => {
        window.location.reload();
      }, 2000)
      refFile.current.value = "";
    } catch (err) {
      loadScreen.style.display = "none";
      setSubmiting(false);
      showErrors(err)
      return err;
    }

    loadScreen.style.display = "none";
    setSubmiting(false);
  }

  return (
    <div className="step-import descargar">
      <div className="paso paso-1">
        <div className="nombre-paso"> Paso 1 </div>
        <div className="descripcion-paso">Descargue su Excell de Productos</div>

        <a
          href="#"
          onClick={downLoadExcell}
          className={"mt-x5 btn btn-block btn-xs btn-flat btn-download ".concat(isSeaching ? 'disabled btn-default' : ' btn-primary')}>
          <span className={isSeaching ? "fa fa-spinner fa-spin spinner" : "fa fa-download"}></span> {isSeaching ? 'Generando Excell' : 'Descargar'}
        </a>
      </div>

      <div className="paso paso-2">
        <div className="nombre-paso"> Paso 2 </div>
        <div className="descripcion-paso">Ponga los datos de su Stock </div>
      </div>

      <form
        encType="multipart/form-data"
        onSubmit={handleSubmit}
        method="post">
        <div className="paso">
          <div className="nombre-paso"> Paso 3 </div>
          <div className="descripcion-paso">Cargue su Excell y presiona el boton Procesar </div>
          <span className={"mt-x5 btn btn-block btn-flat btn-xs btn-file ".concat(isSeaching ? 'disabled' : '').concat(isLoad ? 'btn-success' : 'btn-primary')}>
            <span className={"fa ".concat(isLoad ? "fa-check" : "fa-upload")}></span> {isLoad ? 'Excell Cargado' : 'Cargar Excell Aqui'} 
            
            <input accept=".xlsx" onChange={loadFile} ref={refFile} type="file" />
          </span>
        </div>

        <div className="">
          <button
            type="submit"
            className={'mt-x5 btn btn-block btn-flat btn-default btn-file'.concat(isSubmiting ? 'disabled' : '')}>
            <span className='fa fa-save'> </span> Procesar </button>
        </div>

        {/* <div className="">
          <p style={{ textAlign: 'center', margin: 0 }}> 
            <input type="checkbox" ref={refEstado} defaultChecked={true} /> Ejecutar
          </p>
        </div> */}

      </form>

    </div>
  )
}


if (rootEle) {
  ReactDOM.render(<TomaProductoImport></TomaProductoImport>, rootEle);
}

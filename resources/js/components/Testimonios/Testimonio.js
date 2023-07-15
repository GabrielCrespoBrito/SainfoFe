import React, { createContext, useContext, useEffect, useState } from 'react'
import ReactDOM from 'react-dom';
import styles from '../ClienteIndex/ClienteIndex.module.css';
import ModalTestimonio from './ModalTestimonio';
import TestimonioContext from './TestimonioContext';
import axios from 'axios';
import Helper from '../../Helper';
import { search as searchTestimonio , destroy as destroyTestimonio} from './TestimonioRequest';
// import dovenv

const rootEle = document.getElementById('root-testimonios');

function AddNew() {
  const contex = useContext(TestimonioContext);
  return (
    <div
      onClick={() => {
        contex.setActionCreate(true)
        contex.setIsOpen(true)
      }}
      className={styles['container-add-new']}>
      <button
        className={styles['add-new']}>
        <span className='fa fa-plus'></span> Nuevo
      </button>

    </div>
  )
}


function NoTestimonioContainer() {
  return (
    <div className={styles['container-no-clients']}>
      Registre Testimonios Nuevos
    </div>
  )
}


function TestimonioCard({ data }) {
  const context = useContext(TestimonioContext);
  const handleSelectedClient = () => {
    context.setModel(data);
    context.setIsOpen(true);
  }
  return (
    <div onClick={handleSelectedClient} key={data.id} className={styles['card-cliente']}>
      <div className={styles['card-img']}> <img src={data.path} /> </div>
      <div className={styles['card-name']}>
        {data.cliente.razon_social}
      </div>
      <div className={styles['card-info']}>
        <div className={styles['card-ruc']}> <span className='fa fa-user'></span> {data.representante} </div>
      </div>
      <div className={styles['card-info']}>
        <div className={styles['card-ruc']}> {data.cargo} </div>
      </div>            
    </div>
  )
}

function TestimonioContainers() {

  const { models } = useContext(TestimonioContext);

  if (models.length == 0) {
    return <NoTestimonioContainer></NoTestimonioContainer>
  }

  return (
    <section className={styles['container-clients']}>
      {models.map((model, index) => {
        return <TestimonioCard key={model.id} data={model} />
      })}
    </section>
  )
}

function Testimonio() {
  let [search, setSearch] = useState(false);
  let [actionCreate, setActionCreate] = useState(true);
  let [model, setModel] = useState({});
  let [open, setIsOpen] = useState(false);
  let [models, setModels] = useState([]);
  // let [isSearching , data ] = useAxios(TestimonioUrls.search);
  
    const urls = {
      search: rootEle.dataset.urlSearch,
      searchCliente: rootEle.dataset.urlSearchCliente,
      store: rootEle.dataset.urlStore,
      destroy: rootEle.dataset.urlDelete.replace('xxx', model.id),
      update: rootEle.dataset.urlUpdate.replace('xxx', model.id)
    }


  const searchModels = () => {
    const res = searchTestimonio( urls.search );
    res.then((data) => {
      setModels(() => data.data.models);
    })
  }

  console.log( "model" , model )

  useEffect(() => {
    searchModels();
  }, [search]);

  // const handleSubmit = (data) => {
  //   const url = Helper.IsObjEmpty(model) ? rootEle.dataset.urlStore : rootEle.dataset.urlUpdate.replace('xxx', model.id);

  //   let bodyFormData = new FormData();
  //   bodyFormData.append('razon_social', data.razon_social);
  //   bodyFormData.append('ruc', data.ruc);
  //   bodyFormData.append('sitio', data.sitio);
  //   if (data.image) {
  //     bodyFormData.append('image', data.image);
  //   }
  //   bodyFormData.append('active', data.active);

  //   axios.post(url, bodyFormData)
  //     .then(res => {
  //       Helper.Notificacion.success(res.data.message)
  //       setIsClose();
  //       setSearch(!search);
  //     }).catch(res => {
  //       Helper.IterateErrors(res.response.data.errors);
  //     })

  //   return false;
  // }

  // const handleDelete = () => {
  //   const url = document.getElementById('landing.cliente.root').dataset.urlDelete.replace('xxx', client.id);
  //   axios.post(url, {})
  //     .then(res => {
  //       Helper.Notificacion.success(res.data.message)
  //       setIsClose();
  //       setSearch(!search);
  //     }).catch(res => {
  //       Helper.IterateErrors(res.response.data.errors);
  //     })
  //   return false;
  // }

  const setIsClose = () => {
    setModel({});
    setIsOpen(false)
    setSearch((prevState) => !prevState)
  }

  const successFunc = (res) =>
  {
    Helper.Notificacion.success(res.data.message);
    setIsClose(true);
  }
  
  return (    
    <>
      <TestimonioContext.Provider value={{
        setIsOpen,
        setModel,
        urls,
        successFunc,
        setActionCreate,
        setIsClose,
        setModel,
        models,
        actionCreate,
        model,
      }}>
        <AddNew></AddNew>
        <TestimonioContainers></TestimonioContainers>
        {open && <ModalTestimonio></ModalTestimonio>}
      </TestimonioContext.Provider>
    </>
  );
}


if (rootEle) {
  ReactDOM.render(<Testimonio></Testimonio>, rootEle);
}
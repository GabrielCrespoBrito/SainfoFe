import React, { createContext, useContext, useEffect, useState } from 'react'
import ReactDOM from 'react-dom';
import styles from '../ClienteIndex/ClienteIndex.module.css';
import ModalCaract from './ModalCaract';
import ContCaractContext from './ContCaractContext';
import Helper from '../../Helper';
import { search as searchCaract, destroy as destroyTestimonio } from './ContCaractRequest';

const rootEle = document.getElementById('root-cont');

function AddNew() {
  const contex = useContext(ContCaractContext);
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
      No Hay Nada Registrado
    </div>
  )
}

function ContCaractCard({ data }) {

  const context = useContext(ContCaractContext);
  // console.log("data", context);
  const handleSelectedClient = () => {
    context.setModel(data);
    context.setIsOpen(true);
  }
  
  console.log(data.icon)
  return (
    <div onClick={handleSelectedClient} key={data.id} className={styles['card-cliente']}>
      
      <div className={styles['card-name']}>
        <div style={{ color: data.icon_color,  textAlign: 'center', width: '50px', height: '50px' }} dangerouslySetInnerHTML={{ __html: data.icon }} />
      </div>
      
      <div className={styles['card-name']}>
        {/* <span style={{ padding: '10px', backgroundColor: data.card_color }}></span> */}
        <strong> {data.nombre} </strong>
      </div>

      <div className={styles['card-name']}>
        {data.descripcion}
      </div>

    </div>
  )
}

function TestimonioContainers() {

  const { models } = useContext(ContCaractContext);

  if (models.length == 0) {
    return <NoTestimonioContainer></NoTestimonioContainer>
  }

  return (
    <section className={styles['container-clients']}>
      {models.map((model, index) => {
        return <ContCaractCard key={model.id} data={model} />
      })}
    </section>
  )
}

function ContCaract() {


  let [search, setSearch] = useState(false);
  let [actionCreate, setActionCreate] = useState(true);
  let [model, setModel] = useState({});
  let [open, setIsOpen] = useState(false);
  let [models, setModels] = useState([]);

  const urls = {
    search: rootEle.dataset.urlSearch,
    store: rootEle.dataset.urlStore,
    destroy: rootEle.dataset.urlDelete.replace('xxx', model.id),
    update: rootEle.dataset.urlUpdate.replace('xxx', model.id)
  }

  const searchModels = () => {
    const res = searchCaract(urls.search);
    res.then((data) => {
      setModels(() => data.data.models);
    })
  }

  console.log("model", model)

  useEffect(() => {
    searchModels();
  }, [search]);

  const setIsClose = () => {
    setModel({});
    setIsOpen(false)
    setSearch((prevState) => !prevState)
  }

  const successFunc = (res) => {
    Helper.Notificacion.success(res.data.message);
    setIsClose(true);
  }

  return (
    <>
      <ContCaractContext.Provider value={{
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
        {open && <ModalCaract></ModalCaract>}
      </ContCaractContext.Provider>
    </>
  );
}

if (rootEle) {
  ReactDOM.render(<ContCaract></ContCaract>, rootEle);
}
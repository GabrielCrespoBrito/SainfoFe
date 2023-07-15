import React, { createContext, useContext, useEffect, useState } from 'react'
import ReactDOM from 'react-dom';
import styles from './ClienteIndex.module.css';
import ModalClient from './ModalClient';
import ClienteIndexContext from './ClienteIndexContext';
import axios from 'axios';
import Helper from '../../Helper';




function AddNew() {
  const contex = useContext(ClienteIndexContext);
  return (
    <div 
      onClick={() => {
        contex.setClient({}) 
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


function NoClienteContainer() {
  return (
    <div className={styles['container-no-clients']}>
      Registre Clientes Nuevos
    </div>
  )
}


function Cliente({data}) {
  const isActive = data.active == "1";
  const classCardStatus =  isActive ? styles['card-status-active'] : styles['card-status-inactive']
  const context = useContext(ClienteIndexContext);
  const handleSelectedClient = () => {
    context.setClient(data);
    context.setIsOpen(true);
  }
  return (
    <div onClick={handleSelectedClient} key={data.id} className={styles['card-cliente']}>
      <div className={styles['card-img']}> <img src={data.path}/> </div>
      <div className={styles['card-name']}> {data.razon_social} </div>
      <div className={styles['card-info']}>
        <span className={styles['card-ruc']}> <span className='fa fa-bookmark-o'></span> {data.ruc} </span>
        <span className={classCardStatus}> 
          <span className={isActive ? 'fa fa-eye' : 'fa fa-eye-slash' }></span> {isActive ? 'Activo' : 'Inactiva' }</span>
      </div>
    </div>
  )
}

function ClientContainers({clients}) {
  
  if (clients.length == 0) {
    return <NoClienteContainer></NoClienteContainer>
  }

  return (
    <section className={styles['container-clients']}>
      {clients.map((client, index) => {
        return <Cliente key={client.id} data={client} />
      })}
    </section>
  )
}

function ClienteIndex()
{  
  let [search, setSearch] = useState(false);
  let [client, setClient] = useState({});
  let [open, setIsOpen] = useState(false);
  let [clients, setClients] = useState([]);

  useEffect(() => {
    const url = document.getElementById('landing.cliente.root').dataset.urlSearch;
    axios.post(url)
    .then( data => {
      console.log( "data" , data.data.clients );
      setClients(() => {return data.data.clients})
    })
    .catch( error => console.log("error", error) );
  }, [search]);

  const handleSubmit = (data) => {
    const ele = document.getElementById('landing.cliente.root');
    const url = Helper.IsObjEmpty(client) ? ele.dataset.urlStore : ele.dataset.urlUpdate.replace('xxx', client.id);
    
    let bodyFormData = new FormData();
    bodyFormData.append('razon_social', data.razon_social);
    bodyFormData.append('ruc', data.ruc);
    bodyFormData.append('sitio', data.sitio);
    if(data.image){
      bodyFormData.append('image', data.image);
    }
    bodyFormData.append('active', data.active);
    
    axios.post(url, bodyFormData )
    .then(res => {
      Helper.Notificacion.success(res.data.message)
      setIsClose();
      setSearch(!search);
    }).catch( res => {
      Helper.IterateErrors(res.response.data.errors);
    })    

    return false;
  }

  const handleDelete = () => {
    const url = document.getElementById('landing.cliente.root').dataset.urlDelete.replace('xxx', client.id);
    axios.post(url, {})
      .then(res => {
        Helper.Notificacion.success(res.data.message)
        setIsClose();
        setSearch(!search);
      }).catch(res => {
        Helper.IterateErrors(res.response.data.errors);
      })
    return false;
  }


  const setIsClose = () => {
    setClient({});
    setIsOpen(false)
  }

  return (
    <>
      <ClienteIndexContext.Provider value={{
        setIsOpen, setClient, setIsClose, handleSubmit, handleDelete, setClient, client
      }}>
        <AddNew></AddNew>
        <ClientContainers clients={clients}></ClientContainers>
        { open && <ModalClient></ModalClient>}
      </ClienteIndexContext.Provider>
    </>
  );
}




if (document.getElementById('landing.cliente.root')) {
  window.form_nota_debito = ReactDOM.render(<ClienteIndex></ClienteIndex>, document.getElementById('landing.cliente.root'));
}
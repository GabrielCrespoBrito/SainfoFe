import { createRef, useContext } from "react";
import Helper from "../../Helper";
import Modal from "../Modal/Modal";
import ClienteIndexContext from "./ClienteIndexContext";
import FormCliente from "./FormCliente";

function ModalClient() {
  
  const { setIsClose, client} = useContext(ClienteIndexContext)
  const title = Helper.IsObjEmpty(client) ? "Nuevo Cliente" : client.razon_social;

  return (
    <Modal
      title={title}
      setIsClose={setIsClose}      
      >
      <FormCliente></FormCliente>
    </Modal>
  )
}

export default ModalClient;
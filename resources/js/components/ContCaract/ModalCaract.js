import { useContext } from "react";
import Modal from "../Modal/Modal";
import TestimonioContext from "./ContCaractContext";
import FormCaract from "./FormCaract";
import Helper from "../../Helper";

function ModalCaract() {
  
  const { setIsClose, successFunc, model } = useContext(TestimonioContext)
  const createAction = Helper.IsObjEmpty(model);
  const title = createAction ? "Nueva Caracteristica" : 'Caracteristica';

  return (
    <Modal
      title={title}
      setIsClose={setIsClose}
      successFunc={successFunc}
      >
      <FormCaract model={model} successFunc={successFunc} />
    </Modal>
  )
}

export default ModalCaract;
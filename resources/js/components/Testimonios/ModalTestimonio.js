import { useContext } from "react";
import Modal from "../Modal/Modal";
import TestimonioContext from "./TestimonioContext";
import FormTestimonio from "./FormTestimonio";
import Helper from "../../Helper";

function ModalTestimonio() {
  
  const { setIsClose, successFunc, model } = useContext(TestimonioContext)
  const createAction = Helper.IsObjEmpty(model);
  const title = createAction ? "Nuevo Testimonio" : 'Testimonio';

  console.log("modal", model);

  return (
    <Modal
      title={title}
      setIsClose={setIsClose}
      successFunc={successFunc}
      >
      <FormTestimonio model={model} successFunc={successFunc} />
    </Modal>
  )
}

export default ModalTestimonio;
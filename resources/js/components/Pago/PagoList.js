import { useState } from "react";
import PagoContext from "./PagoContext";

function PagoList() {

  const [ pagos, setPagos ] = useState();
  // return ();
  // return ();
  // return ();
  // return ();
  return 
  <PagoContext.Provider value={{
    pagos
  }}>
    <PagoTable></PagoTable>
  </PagoContext.Provider>
}

export default PagoList;
import { useContext } from "react";
import PagoContext from "./PagoContext";

function Pago () {

  return (
    <h1>loremp</h1>
  )
}

function PagoTable() {

  const [pagos] = useContext(PagoContext);
  return (
    <table className="table table-responsive">
      <thead>
        <td> column 1 </td>
        <td> column 2 </td>
        <td> column 3 </td>
        <td> column 4 </td>
      </thead>
      {pagos && pagos.map((pago) => {
        <Pago data={pago}></Pago>
      })}
    </table>
  )
}

export default PagoTable;
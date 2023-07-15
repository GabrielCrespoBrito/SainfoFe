import Validator from "../Validator";
import Helper from "../../Helper";

class ValidationFormNotaDebito extends Validator {

  validate() {

    let { monto, monedaAbbre } = this.data.state.info;
    // const tipo = this.data.state.tipo;
    // monto = Number(monto);

      const $input_importe = this.data.importeRef.current;
      const importe = Number($input_importe.value);
      if (importe > monto) {
        this.addError(`El Importe ${importe} de La Nota de Debito, No puede ser mayor que el Documento (${monto} ${monedaAbbre})`);
        const importe = $input_importe.focus();
        return this.setSuccess(false);
      }

    return true;
  }

  generateDataForm() {
    
    let data = {
      fecha: this.data.fechaRef.current.value,
      serie: this.data.serieRef.current.value,
      concepto : this.data.conceptoRef.current.value,
      tipoIgv : this.data.tipoIgvRef.current.value,
      importe : this.data.importeRef.current.value,
      _token: Helper.GetCsrfToken()
    };

    this.setDataForm(data);
  }
}

export default ValidationFormNotaDebito
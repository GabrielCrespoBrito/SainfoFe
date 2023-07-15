import Validator from "../Validator";
import Helper from "../../Helper";

class ValidationFormNotaCredito  extends Validator
{
  validate()
  {
    let { monto, monedaAbbre } = this.data.state.info;
    const tipo = this.data.state.tipo;
    monto = Number(monto);
    
    if (tipo == 3) {
      const $input_importe = this.data.importeRef.current;
      const importe = Number($input_importe.value);
      if (importe > monto) {
        this.addError(`El Importe ${importe} de La Nota de Credito, No puede ser mayor que el Documento (${monto} ${monedaAbbre})`);
        const importe = $input_importe.focus();
        return this.setSuccess(false);
      }
    }

    if (tipo == 4) {
      
      const cuotas = this.data.cuotasRef.current.state.cuotas;
      
      if (!cuotas.length) {
        this.addError(`Registre al menos UNA (1) Cuota`)
        return this.setSuccess(false);
      }

      let total = Helper.SumInArray(cuotas, 'monto');

      if (total > monto) {
        this.addError(`Las Montos de las Cuotas (${total} ${monedaAbbre}) Excede la Importe del Documento (${monto} ${monedaAbbre})`)
        return this.setSuccess(false);
      }
    }

    return true;
  }

  generateDataForm()
  {
    let data = {
      tipo: this.data.tipoNotaRef.current.value,
      fecha: this.data.fechaRef.current.value,
      serie: this.data.serieRef.current.value,
      _token: Helper.GetCsrfToken()
    };

    if (this.data.state.tipo == 1) {
      data.motivo = this.data.motivoRef.current.value;
    }

    if (this.data.state.tipo == 2) {
      data.motivo = this.data.motivoRef.current.value;
      data.items = this.data.state.info.items;
    }

    if (this.data.state.tipo == 3) {
      data.concepto = this.data.conceptoRef.current.value;
      data.tipoIgv = this.data.tipoIgvRef.current.value;
      data.importe = this.data.importeRef.current.value;
    }

    if (this.data.state.tipo == 4) {
      data.motivo = this.data.motivoRef.current.value;
      data.cuotas = this.data.cuotasRef.current.state.cuotas;
    }

    this.setDataForm(data);
  }
} 

export default ValidationFormNotaCredito;

class PrintTest {

  ticket_ready = false;
  line_width = 47;
  data;
  conector;
  nombre_impresora;
  copy_qty;
  func_success_print;
  func_error_print;

  // Constructor
  constructor(data = [], nombre_impresora, copy_qty = 1, func_success_print = null, func_error_print = null) {

    this.data = data;
    this.nombre_impresora = nombre_impresora;
    this.copy_qty = copy_qty;
    this.func_success_print = func_success_print;
    this.func_error_print = func_error_print;

    this.conector = new ConectorPluginV3();
  }


  formatText(text) {
    return text + "\n";
  }

  getLineaSeparadora(caracter = '-') {
    return this.formatText(caracter.repeat(this.line_width));
  }

  /**
   * 
   * @param {*} text 
   * @param {*} size_column 
   * @param {*} text_align 
   * @returns 
   */
  getTextoSize(text, size_column, align_left = true, cutOverSizing = true, size_text_cercano = 0) {
    let text_length = text.length;

    // Si el tamaño del texto es del mismo que la columna
    if (text_length == size_column) {
      return text;
    }

    // Si el tamaño es menor al de la columna, rellenar con espacios
    else if (text_length < size_column) {
      let spaces_str = " ".repeat(size_column - text_length);
      return align_left ? text.concat(spaces_str) : spaces_str.concat(text);
    }

    // Si el texto es mayor
    else {

      // Recortarlo
      if (cutOverSizing) {
        return text.slice(0, size_column);
      }

      // De lo contrario, tratar string para la/s siguientes linea 
      else {

        let text_rest = '';
        let text_part = '';
        let text_parts = [];
        let text_parts_len = Math.round(text_length / size_column);

        // console.log({ text_parts_len });
        // return;

        for (let index = 0; index <= text_parts_len; index++) {

          text_part = text.slice(0, size_column);

          text = text.slice(size_column);

          // Poner caracteres a la izquiera para la separaciòn si es el caso
          if (index != 0) {
            text_part = size_text_cercano ? " ".repeat(size_text_cercano).concat(text_part) : text_part;
          }

          let end_caracter = (index === text_parts_len) ? '' : "\n";
          text_part.concat(end_caracter);
          text_parts.push(text_part);
          text_rest += text_part;
        }

        console.log({ text_parts, text_rest });
        return text_rest;

      }
    }
  }

  /**
   * Informacion de la empresa
   * @return
   */
  makeEmpresaData() {
    this.conector
      .EstablecerAlineacion(ConectorPluginV3.ALINEACION_CENTRO)
      // .DescargarImagenDeInternetEImprimir(this.data['empresa_logo_path']);
      // .establecerTamanioFuente(2, 1)
      // .establecerEnfatizado(1)
      // .textoConSalto(this.data['empresa_nombre'])
      // .establecerEnfatizado(0)
      // .establecerTamanioFuente(1, 1)
      // .textoConSalto(this.data['empresa_direccion'])
      // .textoConSalto(this.data['empresa_ruc'])
      // .textoConSalto(this.data['empresa_telefonos'])
      // .textoConSalto(this.data['empresa_correos'])
      // .texto(this.getLineaSeparadora())
  }


  /**
   * Informacion del Pie
   * @return
   */
  makeMessage() {

    this.conector
      .EstablecerTamañoFuente(2, 1)
      .EscribirTexto('PRUEBA DE IMPRESIÒN: ')
      .EscribirTexto('IMPRESORA: ' + this.nombre_impresora );
      // .establecerTamanioFuente(1, 1)
  }

  /**
   * Cambiar el formato
   *
   * @return ConectorPlugin 
   */
  makeTicket() {
    if (this.ticket_ready) {
      return;
    }

    // this.conector.cortar(); 
    // this.makeEmpresaData();
    this.makeMessage();
    // this.conector.cortar();
    this.ticket_ready = true;
  }

  errorFunc(e) {
    console.log("errorFunc", this);
    // if (this.func_error_print) {
    // this.func_error_print(this.data, e);
    // }
  }

  printTicket() {
    this.conector.imprimirEn(this.nombre_impresora)
      .then(responsePrint => {
        if (responsePrint === true) {
          notificaciones('Al parecer se ha realizado la impresiòn correctamente, por favor revisar confirmar en su impresora', 'success');
        }
        else {
          notificaciones( responsePrint, 'error' );
          if (this.func_error_print) {
            this.func_error_print(this.data, responsePrint);
          }
        }
      })
      .catch(this.errorFunc)
  }

  // Print
  print() {
    this.makeTicket();
    return this.printTicket()
  }  
}
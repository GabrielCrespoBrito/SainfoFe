class printTicket {

  ticket_ready = false;
  line_width = 47;
  data;
  conector;
  nombre_impresora;
  copy_qty;
  func_success_print;
  func_error_print;

  // Constructor
  constructor(data = [], nombre_impresora = "POS-80", copy_qty = 1, func_success_print = null, func_error_print = null) {

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
      .Iniciar()
     .EstablecerAlineacion(ConectorPluginV3.ALINEACION_CENTRO)
     
    if (this.data['empresa_logo_path']){
      // this.conector.DescargarImagenDeInternetEImprimir(this.data['empresa_logo_path'], ConectorPluginV3.TAMAÑO_IMAGEN_NORMAL, 160)
    }
  
    this.conector
      .EstablecerTamañoFuente(2, 1)
      .EstablecerEnfatizado(true)
      .EscribirTextoFeed(this.data['empresa_nombre'])
      .EstablecerEnfatizado(false)
      .EstablecerTamañoFuente(1, 1)
      .EscribirTextoFeed(this.data['empresa_direccion'])
      .EscribirTextoFeed(this.data['empresa_ruc'])
      .EscribirTextoFeed(this.data['empresa_telefonos'])
      .EscribirTextoFeed(this.data['empresa_correos'])
      .EscribirTexto(this.getLineaSeparadora())
    }

  /**
   * Informacion del documento
   * @return
   */
  makeDocumentoId() {
    this.conector
      .EstablecerEnfatizado(true)
      .EstablecerTamañoFuente(2, 1)
      .EscribirTextoFeed(this.data['documento_nombre'])
      .EscribirTextoFeed(this.data['documento_id'])
      .EstablecerTamañoFuente(1, 1)
      .EstablecerEnfatizado(false)
  }

  /**
   * Informacion del cliente
   * @return
   */
  makeClienteData() {
    this.conector
      .EstablecerAlineacion(ConectorPluginV3.ALINEACION_IZQUIERDA)
      .EscribirTextoFeed(this.data['cliente_razon_social'])
      .EscribirTextoFeed(this.data['cliente_documento'])
      .EscribirTextoFeed(this.data['cliente_direccion'])
      .EscribirTexto(this.getLineaSeparadora())
  }


  /**
   * Informacion del observacion
   * @return
   */
  makeObservacion() {
    this.conector
      .EscribirTextoFeed(this.data['documento_observacion'])
      .EscribirTexto(this.getLineaSeparadora())
  }


  /**
   * Informacion del cliente
   * @return
   */
  makeInformacionDocumento() {
    // 22-25

    console.log( this.data );

    this.conector
      .EscribirTextoFeed(this.getTextoSize('Fecha:', 22) + this.getTextoSize(this.data['documento_fecha'], this.line_width - 22))
      .EscribirTextoFeed(this.getTextoSize('Vendedor:', 22) + this.getTextoSize(this.data['documento_vendedor'], this.line_width - 22))
      .EscribirTextoFeed(this.getTextoSize('Forma de Pago:', 22) + this.getTextoSize(this.data['documento_forma_pago'], this.line_width - 22))
      .EscribirTextoFeed(this.getTextoSize('Guia/s:', 22) + this.getTextoSize(this.data['documento_guias'], this.line_width - 22, true, true))
      .EscribirTextoFeed(this.getTextoSize('Responsable:', 22) + this.getTextoSize(this.data['documento_responsable'], this.line_width - 22))
      .EscribirTextoFeed(this.getTextoSize('Ord.Compra:', 22) + this.getTextoSize(this.data['documento_orden_compra'], this.line_width - 22))
      .EscribirTexto(this.getLineaSeparadora())
  }

  /**
   * Informacion de tabla de productos
   * @return
   */
  makeTableProducts() {

    // Cabecera de la tabla
    this.conector
      .EstablecerEnfatizado(true)
      .EscribirTextoFeed(
        this.getTextoSize('Unid', 7) +
        this.getTextoSize('Descripcion', this.line_width - 7))
      .EscribirTextoFeed(
        this.getTextoSize('Cant.', 15) +
        this.getTextoSize('P.Unit.', 18) +
        this.getTextoSize('Importe', 14, false, true)
      )
      .EstablecerEnfatizado(false)
      .EscribirTexto(this.getLineaSeparadora())


    // Productos
    for (let index = 0; index < this.data['items'].length; index++) {
      let item = this.data['items'][index];

      this.conector
        .EscribirTextoFeed(
          this.getTextoSize(item.unidad, 7) +
          this.getTextoSize(item.descripcion, this.line_width - 7, true, false, 7)
        )
        .EscribirTextoFeed(
          this.getTextoSize(String(item.cantidad), 15) +
          this.getTextoSize(String(item.precio_unitario), 18) +
          this.getTextoSize(String(item.importe), 14, false, true)
        )
        .EscribirTexto(this.getLineaSeparadora())
    }
  }

  /**
   * El total en texto
   */
  makeInfoAnexo() {

    // Productos
    for (let index = 0; index < this.data['infos_adicional'].length; index++) {
      let info = this.data['infos_adicional'][index];
      this.conector
        .EscribirTextoFeed(info.descripcion)
        .EscribirTexto(this.getLineaSeparadora())
    }


    // Monto
    this.conector
      .EstablecerEnfatizado(true)
      .EscribirTextoFeed(this.data['monto_letra'])
      .EscribirTexto(this.getLineaSeparadora())
      .EstablecerEnfatizado(false)
  }

  /**
   * Informacion del totales
   * @return
   */
  makeTotals() {

    this.conector.EstablecerEnfatizado(true)

    for (let index = 0; index < this.data['totals'].length; index++) {
      const total = this.data['totals'][index];

      this.conector.EscribirTextoFeed(
        this.getTextoSize(total.descripcion, 25) +
        this.getTextoSize(this.data['moneda_abbreviatura'], 5) +
        this.getTextoSize(total.value, 17, false, false)
      )
    }

    this.conector
      .EstablecerEnfatizado(false)
      .EscribirTexto(this.getLineaSeparadora())
  }

  /**
   * Informacion del Cuentas
   * @return
   */
  makeCuentas() {

    this.conector
      .EstablecerEnfatizado(true)
      .EscribirTextoFeed('CUENTAS')
      .EstablecerEnfatizado(false);

    // Cuentas
    for (let index = 0; index < this.data['bancos'].length; index++) {
      let cuenta = this.data['bancos'][index];
      let cuenta_str = cuenta.banco_nombre + ' ' + cuenta.banco_moneda + cuenta.banco_cuenta;
      this.conector.EscribirTextoFeed(cuenta_str);
    }

    this.conector.EscribirTexto(this.getLineaSeparadora())
  }

  /**
   * Informacion del QR
   * @return
   */
  makeQr() {
    // let informacion_qr = this.data['qr_data'];

    // this.conector
    //   .EstablecerAlineacion(ConectorPluginV3.ALINEACION_CENTRO)
    //   .ImprimirCodigoQr(informacion_qr, 160, ConectorPluginV3.RECUPERACION_QR_MEJOR, ConectorPluginV3.TAMAÑO_IMAGEN_NORMAL)
    //   .EstablecerAlineacion(ConectorPluginV3.ALINEACION_IZQUIERDA);
  }

  /**
   * Informacion del Pie
   * @return
   */
  makePie() {

    let representacion_impresora = 'Representaciòn impresa de:';
    let consulta = 'Esta puede ser consultada en:';

    this.conector
      .EscribirTextoFeed('Resumen: ' + this.data['documento_hash'])
      .EscribirTextoFeed('Hora: ' + this.data['documento_hora'])
      .EscribirTextoFeed('Peso: ' + this.data['documento_peso'] + ' Kgs.')
      .EscribirTextoFeed(representacion_impresora)
      .EstablecerEnfatizado(true)
      .EscribirTextoFeed(this.data['documento_nombre'])
      .EstablecerEnfatizado(false)
      .EscribirTextoFeed(consulta)
      .EstablecerEnfatizado(true)
      .EscribirTextoFeed($.trim(this.data['direccion_consulta']))
      .EstablecerEnfatizado(false)
      .Corte(1)
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
    this.makeEmpresaData();
    this.makeDocumentoId();
    this.makeClienteData();
    this.makeObservacion();
    this.makeInformacionDocumento();
    this.makeTableProducts();
    this.makeInfoAnexo();
    this.makeTotals();
    this.makeCuentas();
    this.makeQr();
    this.makePie();
    // this.conector.cortar();
    this.ticket_ready = true;
  }

  errorFunc(e) {
    console.log("errorFunc", this);
  }

  async printExe() {
    await this.conector.imprimirEn(this.nombre_impresora)
      .then(responsePrint => {
        if (responsePrint === true) {
          console.log("Print Success");
          if (this.func_success_print) {
            this.func_success_print(this.data);
          }
        }
        else {
          console.log("Print Error " + responsePrint);
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

    for (let index = 0; index < this.copy_qty; index++) {
      if (this.printExe() == false) {
        return false;
      }
    }

    return true;
  }
}


window.printTicket = printTicket; 
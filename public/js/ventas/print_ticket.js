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

    this.conector = new ConectorPlugin();
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
      .establecerJustificacion(ConectorPlugin.Constantes.AlineacionCentro)
      .imagenDesdeUrl(this.data['empresa_logo_path'] )
      .establecerTamanioFuente(2, 1)
      .establecerEnfatizado(1)
      .textoConSalto(this.data['empresa_nombre'])
      .establecerEnfatizado(0)
      .establecerTamanioFuente(1, 1)
      .textoConSalto(this.data['empresa_direccion'])
      .textoConSalto(this.data['empresa_ruc'])
      .textoConSalto(this.data['empresa_telefonos'])
      .textoConSalto(this.data['empresa_correos'])
      .texto(this.getLineaSeparadora())
  }

  /**
   * Informacion del documento
   * @return
   */
  makeDocumentoId() {
    this.conector
      .establecerEnfatizado(1)
      .establecerTamanioFuente(2, 1)
      .textoConSalto(this.data['documento_nombre'])
      .textoConSalto(this.data['documento_id'])
      .establecerTamanioFuente(1, 1)
      .establecerEnfatizado(0)
  }

  /**
   * Informacion del cliente
   * @return
   */
  makeClienteData() {
    this.conector
      .establecerJustificacion(ConectorPlugin.Constantes.AlineacionIzquierda)
      .textoConSalto(this.data['cliente_razon_social'])
      .textoConSalto(this.data['cliente_documento'])
      .textoConSalto(this.data['cliente_direccion'])
      .texto(this.getLineaSeparadora())
  }


  /**
   * Informacion del observacion
   * @return
   */
  makeObservacion() {
    this.conector
      .textoConSalto(this.data['documento_observacion'])
      .texto(this.getLineaSeparadora())
  }


  /**
   * Informacion del cliente
   * @return
   */
  makeInformacionDocumento() {
    // 22-25
    this.conector
      .textoConSalto(this.getTextoSize('Fecha:', 22) + this.getTextoSize(this.data['documento_fecha'], this.line_width - 22))
      .textoConSalto(this.getTextoSize('Vendedor:', 22) + this.getTextoSize(this.data['documento_vendedor'], this.line_width - 22))
      .textoConSalto(this.getTextoSize('Forma de Pago:', 22) + this.getTextoSize(this.data['documento_forma_pago'], this.line_width - 22))
      .textoConSalto(this.getTextoSize('Guia/s:', 22) + this.getTextoSize(this.data['documento_guias'], this.line_width - 22, true, true ))
      .textoConSalto(this.getTextoSize('Responsable:', 22) + this.getTextoSize(this.data['documento_responsable'], this.line_width - 22))
      .textoConSalto(this.getTextoSize('Ord.Compra:', 22) + this.getTextoSize(this.data['documento_orden_compra'], this.line_width - 22))
      .texto(this.getLineaSeparadora())
  }

  /**
   * Informacion de tabla de productos
   * @return
   */
  makeTableProducts() {

    // Cabecera de la tabla
    this.conector
      .establecerEnfatizado(1)
      .textoConSalto(
        this.getTextoSize('Unid', 7) + 
        this.getTextoSize('Descripcion', this.line_width - 7))
      .textoConSalto(
        this.getTextoSize( 'Cant.', 15) +
        this.getTextoSize( 'P.Unit.', 18) +
        this.getTextoSize( 'Importe', 14 , false, true )
      )
      .establecerEnfatizado(0)
      .texto(this.getLineaSeparadora())


    // Productos
    for (let index = 0;  index < this.data['items'].length; index++) {
        let item = this.data['items'][index];

        this.conector
          .textoConSalto(
            this.getTextoSize(item.unidad, 7) + 
            this.getTextoSize(item.descripcion , this.line_width - 7, true , false, 7)
          )
          .textoConSalto(
            this.getTextoSize(String(item.cantidad) , 15) +
            this.getTextoSize(String(item.precio_unitario)   , 18) +
            this.getTextoSize(String(item.importe)  , 14, false, true)
          )
          .texto(this.getLineaSeparadora())
      }
  }

  /**
   * El total en texto
   */
  makeInfoAnexo(){


    console.log(this.data);

    // Productos
    for (let index = 0; index < this.data['infos_adicional'].length; index++) {
      let info = this.data['infos_adicional'][index];
      this.conector
        .textoConSalto(info.descripcion)
        .texto(this.getLineaSeparadora())
    }


    // Monto
    this.conector
      .establecerEnfatizado(1)
      .textoConSalto(this.data['monto_letra'])
      .texto(this.getLineaSeparadora())
      .establecerEnfatizado(0)
  }

  /**
   * Informacion del totales
   * @return
   */
  makeTotals() {    

    this.conector.establecerEnfatizado(1)

    for (let index = 0; index < this.data['totals'].length; index++) {
      const total = this.data['totals'][index];

        this.conector.textoConSalto(
          this.getTextoSize( total.descripcion, 25) +
          this.getTextoSize( this.data['moneda_abbreviatura'], 5) +
          this.getTextoSize( total.value, 17, false, false)
        )
    }

    this.conector
    .establecerEnfatizado(0)
    .texto(this.getLineaSeparadora())
  }

  /**
   * Informacion del Cuentas
   * @return
   */
  makeCuentas() {

    this.conector
      .establecerEnfatizado(1)
      .textoConSalto('CUENTAS')
      .establecerEnfatizado(0);
      
    // Cuentas
    for (let index = 0; index < this.data['bancos'].length; index++) {
      let cuenta = this.data['bancos'][index];
      let cuenta_str = cuenta.banco_nombre + ' ' + cuenta.banco_moneda + cuenta.banco_cuenta;
      this.conector.textoConSalto(cuenta_str);
    }

    this.conector.texto(this.getLineaSeparadora())
  }

  /**
   * Informacion del QR
   * @return
   */
  makeQr() {
    let informacion_qr = this.data['qr_data'];
    
    this.conector
    .establecerJustificacion(ConectorPlugin.Constantes.AlineacionCentro)
    .qrComoImagen(informacion_qr)
    .establecerJustificacion(ConectorPlugin.Constantes.AlineacionIzquierda);

  }

  /**
   * Informacion del Pie
   * @return
   */
  makePie() {

    let representacion_impresora = 'Representaciòn impresa de:';
    let consulta = 'Esta puede ser consultada en:';

    this.conector
    .textoConSalto( 'Resumen: ' +  this.data['documento_hash'] )
    .textoConSalto( 'Hora: ' + this.data['documento_hora'] )
    .textoConSalto( 'Peso: ' + this.data['documento_peso'] + ' Kgs.' )
    .textoConSalto( representacion_impresora)
    .establecerEnfatizado(1)
    .textoConSalto(this.data['documento_nombre'])
    .establecerEnfatizado(0)
    .textoConSalto(consulta)
    .establecerEnfatizado(1)
    .textoConSalto( $.trim(this.data['direccion_consulta']))
    .establecerEnfatizado(0)
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

  errorFunc(e)
  {
    console.log( "errorFunc" , this);
    // if (this.func_error_print) {
      // this.func_error_print(this.data, e);
    // }
   }

  printTicket()
  {   
    this.conector.imprimirEn( this.nombre_impresora )
        .then(responsePrint => {
          console.log({ responsePrint });

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
      if(this.printTicket() == false){
        return false;
      }
    }

    return true;
  }
}
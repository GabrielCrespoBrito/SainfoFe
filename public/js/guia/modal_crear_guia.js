let appCG = {

  el : '#modalData',
  eles : {
    form: '#formCg',
    btnRemove: '.remove-item',
  },

  successActions : {},

  data :  {
    id : null,
    url_create: '/guia/@@/create-simply'
  },

  /**
   * La url para obtener el formulario
   * @param {*} url_tipo 
   * @returns 
   */
  getUrlCreate : function(url_tipo)
  {
    return this.data.url_create.replace('@@', this.data.id );
  },

  setID : function(id){
    this.data.id = id;
  },

  getID: function (id) {
    return this.data.id;
  },

  initDatePlugin : function()
  {
    $('#fecha_emision', this.el).datepicker({    
      autoclose: true,
      format: 'yyyy-mm-dd',
    });
  },

  /**
   * Cargar informaciòn por ajax y mostrar modal
   * @param {string} data 
   */
  showModal : function(id){

    if(this.data.id == id){
      // $(this.el).modal('show')
      // return;
    }

    this.setID(id);
    
    // Llamada de ajaxs para traer informaciòn y ponerla
    ajaxs( {}, this.getUrlCreate(),
      {
        success: (html) => {
          let $modal =  $(this.el);
          $modal.find('.modal-dialog').attr('class', 'modal-dialog modal-lg');
          $modal.find('.modal-title').text('Creaciòn de guia de remisiòn');
          $modal.find('.modal-body').empty();
          $modal.find('.modal-body').html(html);
          this.initDatePlugin();
          $modal.modal('show');
        }
      }
    )

  },

  hideModal: function () {
    $(this.el).modal('hide')
  },



  registerSuccessActions : function(funcs)
  {
    for (const func_key in funcs) {
      this.successActions[func_key] = funcs[func_key];
    }
  },


  executeSuccessActionsRegistered : function(data){
    if ($.isEmptyObject(this.successActions.length)){
      for (const func_key in this.successActions) {
        this.successActions[func_key](data);
      }
    }
  },

  saveForm: function (e) {
    e.preventDefault();
    $("#load_screen").show();
    let $form = $(this.eles.form);
    
    $.ajax({
      url : $form.attr('action'),
      data : $form.serialize(),
      type: 'post',
      success : function(data){
        notificaciones( data.message, "success")
        this.hideModal();
        this.executeSuccessActionsRegistered(data);
      }.bind(this),
      complete : function(){
        $("#load_screen").hide();
      },
      error : function(data){
        if (data.responseJSON) {
          let errors = data.responseJSON.errors;
          let mensaje = data.responseJSON.message;
          window.showError(errors, mensaje);
        }
        else {
          notificaciones(data.statusText, 'error');
        }
      }
    });
    return false;
  },

  removeItem : function(e){

    e.preventDefault();

    let $tbody = $(this).parents('tbody');
    let $tr = $(this).parents('tr');

    if( $tbody.find('tr').length > 1 ){
      $tr.remove();
      return;
    }
  },

  events : function(){    
    $(this.el).on('submit', this.eles.form, this.saveForm.bind(this) )
    $(this.el).on('click', this.eles.btnRemove, this.removeItem )
  },

  /**
   * Iniciar lo que se tenga que inicializar
   */
  init : function(){
    this.events();
  }
}
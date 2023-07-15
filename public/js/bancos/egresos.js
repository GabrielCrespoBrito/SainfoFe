let AppCajaEgreso = {

  parent : "#modalEgreso",
  eles : {},
  action : "create",
  model : null,
  is_init : false,
  inits_value : {
    soles : 0,
    dolar : 0
  },

  set_successCallback : function(func){
    this.successCallback = func;
  },

  successCallbackDelete : function(data){
    console.log( "successDeleteCallback", data );
  },

  set_create_action : function(){
    return this.set_action('create')
  },

  set_edit_action : function(){
    return this.set_action('edit')
  },

  set_show_action : function(){
    return this.set_action('show')
  },

  set_action : function(action){
    return this.action = action;
  },

  get_action : function(){
    return this.action
  },

  successCallback : function(data){},

  get_url : function(){
    if( this.get_action() == "create" ){
      return this.eles.button_submit.data('direction');      
    }
    else {
      return this.eles.button_submit.data('direction_update');            
    }
  },

  show : function(){
    this.init();
    $(this.parent).modal();
  },

  clean : function(){
    $( "input[type=text] , input[type=number] ", this.parent).val("")
    $("select", this.parent ).prop('selected' , false );
    this.eles.autoriza.val( this.eles.autoriza.data('default') );
    this.eles.fecha.datepicker('setDate',this.eles.fecha.data('default'));    
  },

  create : function(){
    this.init();
    this.clean();
    this.set_create_action();
    $(this.parent).modal();
  },

  search : function(id){
  },

  show : function(data){
    this.init();
    this.clean();
    this.set_show_action();
    this.id = data.Id;
    Helper__.set_data_form( this.parent , data );    
    $(this.parent).modal();
  },

  delete : function(id = null, url ){
    let data = { 'id_movimiento' : id  };
    let funcs = { success : this.successCallbackDelete.bind(this) };
    console.log("data url" , data , url )
    ajaxs( data , url , funcs  );
  },


  callbackForm : function(ele,value,data){
    console.log("callbackForm", arguments );
    if( data.MonCodi == "01" ){
      ele.val(value)
    } 
    else {
      ele.val(data.CANEGRD);
    }
  }.bind(this),

  edit : function(data){
    this.init();
    this.clean();
    this.set_edit_action();        
    this.id = data.Id;
    console.log("data",data);
    Helper__.set_data_form( this.parent , data , false , {'CANINGS':this.callbackForm} );
    this.show_sections();
    $(this.parent).modal();
  },

  down : function(callback){
    $(this.parent).modal("hide");   
    callback();
  },

  get_data : function(){
    let data = this.eles.form.serialize();
    if( this.get_action() != "create" ){
      data += '&id_movimiento=' + this.id;     
    }

    return data;
  },

  send : function(){   

    let data = this.get_data();
    let funcs =  {
      success : function(data){
        notificaciones(data.message, "success");
        this.down(function(){
          setTimeout(function(){
            window.location.reload();                      
          },1000)
        });
      }.bind(this),
    };

    ajaxs(data , this.get_url() , funcs);
  },


  show_sections : function(){
    let value = this.eles.egreso_tipo.val();
    $("[data-id]" , this.parent)
    .hide()
    .filter("[data-id=" + value + "]").show();

    return value;
  },

  change_egreso : function(){


    let value = this.show_sections();

    if( value == "020" ){
      this.eles.nombre.val( $("[name=personal_id] option:selected").text() );
    }

    else if( value == "006" ){
      this.eles.nombre.val( "RETIRO EFECTIVO" );
    }

    else if( value == "015" ){
      this.eles.nombre.val("TRANSFERENCIA DE EFECTIVO" );
    }

    else if( value == "09" ){
      this.eles.nombre.val( "TRANSF.BANCO" );
    }

  },

  events : function(){
    this.eles.button_submit.on('click' , this.send.bind(this));
    this.eles.egreso_tipo.on('change' , this.change_egreso.bind(this) );    
  },

  set_eles : function(){
    this.eles = {
      form : $('form' , this.parent ),
      button_submit : $( ".save" , this.parent),
      modal : $(this.parent),
      autoriza : $( "[name=autoriza]" ,  this.parent),
      title : $( ".modal-title" ,  this.parent),
      fecha : $( "[name=fecha]" ,  this.parent), 
      egreso_tipo : $("[name=egreso_tipo]" , this.parent ),
      nombre : $("[name=nombre]" , this.parent ),
    };
  },

  init : function(){
    if( !this.is_init ){
      this.set_eles();
      this.events();
      this.is_init = true
    }
  },
};

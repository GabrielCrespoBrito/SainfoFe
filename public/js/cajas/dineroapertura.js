let AppCajaApertura = {

  parent : "#modalApertura",
  eles : {},
  is_init : false,
  inits_value : {
    soles : 0,
    dolar : 0
  },

  set_successCallback : function(func){
    this.successCallback = func;
  },

  successCallback : function(data){},

  get_url : function(){
    return this.eles.button_submit.data('url');
  },

  show : function(soles,dolar){
    soles = Number(soles);
    dolar = Number(dolar);

    this.init();
    console.log( typeof soles , typeof dolar );
    this.inits_value.soles = soles;
    this.inits_value.dolar = dolar;    
    this.eles.inputsoles.val(soles);
    this.eles.inputdolar.val(dolar);    
    // this.eles.inputdolar[0].value = dolar;    

    $(this.parent).modal();
  },

  down : function(){
    $(this.parent).modal("hide");   
  },

  validate : function(){
    return validateNumber(this.eles.inputsoles.val()) && validateNumber(this.eles.inputdolar.val());
  },

  send : function(){   

    if( !this.validate() ){
      notificaciones("Escriba los montos correctamente" , "error");
      return;
    }

    let data =   {
      CANINGS : this.eles.inputsoles.val(),
      CANINGD : this.eles.inputdolar.val(),
    };

    if(data.CANINGS == this.inits_value.soles && data.CANINGD == this.inits_value.dolar){
      notificaciones("Establezca nuevos valores" , "warning");
      return;
    }

    let funcs =  {
      success : function(data){
        notificaciones(data.message, "success");
        this.successCallback(data);
        this.down();
      }.bind(this),
    };  

    ajaxs(data , this.get_url() , funcs);
  },

  events : function(){
    this.eles.button_submit.on('click' , this.send.bind(this));
  },

  set_eles : function(){
    this.eles.button_submit = $( ".save" , this.parent);
    this.eles.inputsoles    = $( ".ingreso_soles" , this.parent);    
    this.eles.inputdolar    = $( ".ingreso_dolar" , this.parent);
    this.eles.modal         = $(this.parent);
  },

  init : function(){
    if( !this.is_init ){
      this.set_eles();
      this.events();
      this.is_init = true
    }
  },
};


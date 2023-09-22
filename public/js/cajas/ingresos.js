let AppCajaIngreso = 
{

  parent : "#modalIngreso",
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
    console.log(this.eles.fecha.data('default'));
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

  callbackForm : function(ele,value,data){
    // console.log("arguments",arguments);
    if( data.MonCodi == "01" ){
      ele.val(value)
    } 
    else {
      ele.val(data.CANINGD);
    }
  }.bind(this),


  setMotivo: function (data)
  {
    console.log( "setMotivo" , data );

    let id = null;
    let text = null;

    if( data.tipo_ingreso ){
      id = data.tipo_ingreso.IngCodi;
      text = data.tipo_ingreso.IngNomb
    }

    $("[name=motivo]", this.parent).attr({
      'data-id': id,
      'data-text': text,
    });

    $("[name=motivo]", this.parent)
      .select2('destroy')
      .empty()

    this.initSelect2();
  },

  initSelect2: function ()
  {
    initSelect2(`${this.parent} [name=motivo]`);
  },

  edit : function(data){
    this.init();
    this.clean();
    this.set_edit_action();        
    this.id = data.Id;  
    console.log({data});
    this.setMotivo(data);

    $("[name=motivo]").find('option[value=' + data.EgrIng + ']').prop('selected', true);
    $("[name=otro_doc]").val(data.OTRODOC);
    $("[name=nombre]").val(data.MocNomb);
    $("[name=autoriza]").val(data.AUTORIZA);
    
    Helper__.set_data_form( this.parent , data , false , { 'CANINGS' : this.callbackForm }  );
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
    $("#load_screen").show();

    // console.log("data", data );
    let funcs =  {
      success : function(data){
      notificaciones('Acción Exitosa', "success");
        window.table_movimientos.draw();
        $("#load_screen").hide();

        this.down(function(){
          setTimeout(function(){
            // window.table_movimientos.draw();
            // window.location.reload();                      
          },1000)
        });
      }.bind(this),
    };

    ajaxs(data , this.get_url() , funcs);
  },

  events : function(){
    this.eles.button_submit.on('click' , this.send.bind(this));
  },

  set_eles : function(){
    this.eles = {
      form : $('.form' , this.parent ),
      button_submit : $( ".save" , this.parent),
      modal : $(this.parent),
      autoriza : $( "[name=autoriza]" ,  this.parent),
      title : $( ".modal-title" ,  this.parent),
      fecha : $( "[name=fecha]" ,  this.parent),      
    };
  },

  init : function(){
    if( !this.is_init ){
      this.set_eles();
      this.events();
      this.initSelect2();
      this.is_init = true
    }
  },
};
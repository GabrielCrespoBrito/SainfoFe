let AppEnvioCorreo = {

	parent : "#modalEnvioCorreo",
	eles : {},
	id : null,

	set_id : function(id){
		this.id = id;
	},

	get_id : function(){
		return this.id;
	},

	get_url : function(){
		let url = this.eles.button_submit.data('url');
		return url.replace('XX' , this.id );
	},

	show : function(data = false){
		if(data){ 
			Helper__.set_data_form( this.parent , data); 
		};
		$(this.parent).modal();
	},

	down : function(){
		$(this.parent).modal("hide");		
	},

	validate : function(){
		return isValidEmail(this.eles.to.val());
	},

	set_state : function( state ){
		switch(state){
			case "create":
			this.eles.button_submit.removeClass('disabled').text('Enviar');
			this.eles.title.html("Enviar correo");
			break;
			case "send" :
			this.eles.button_submit.addClass('disabled').text('Enviando');
			this.eles.title.html("<span class='fa fa-spinner fa-spin'></span> Enviando.. ");
			break;
		}
	},

	send : function(){		
		if( !this.validate() ){
			notificaciones("Escriba un email valido" , "error");
			return;
		}

		let data =   {
			to 			: this.eles.to.val(),
			message : this.eles.message.val(),
			subject : this.eles.subject.val(),
		};

		let funcs =  {
			success : function(data){
				this.set_state('create');
				notificaciones(data.message, "success"); 
				this.down();
			}.bind(this),
			complete : function(data){
				this.set_state('create');
			}.bind(this)
		};	

		this.set_state('send');
		ajaxs(data , this.get_url() , funcs);
	},

	events : function(){
		this.eles.button_submit.on('click' , this.send.bind(this)  );	
	},

	set_eles : function(){
		this.eles.button_submit = $( ".send_correo" , this.parent);
		this.eles.subject = $( "[name=subject]" , this.parent);
		this.eles.message = $( "[name=message]" , this.parent);		
		this.eles.to = $( "[name=to]" , this.parent);				
		this.eles.modal = $(this.parent);
		this.eles.title = $( ".modal-title" , this.parent);
	},

	init : function(){
		this.set_eles();
		this.events();
	},
};

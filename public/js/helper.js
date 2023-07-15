function Helper()
{
	this.settings = {

		// ajaxs
		ajax: {
			isHeaderNotSend : true,
			executing : false,
			blockIsExecuting : false,
			defaultFuncs : {
				success  : function(){},
				error 	 : function(){},
				complete : function(){},
				before   : function(){},				
			},
			defults : {
				type : 'post',
			}
		}, 
		// ---------------------------------

		// notificaciones
		noti : {
			defaults : {
        'position'  : 'top-center',
        'hideAfter' : 3000, 
        "hideEasing": "linear",
        'showHideTransition' : 'slide' 
			},
		},
		// ---------------------------------
		b: 2
	};


	// --------------------- Ajaxs ---------------------

	// incluir tockens en cabeceras de solicitudes ajaxs
	this.sendAjaxHeader = function(){
		$.ajaxSetup({headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}});
		this.settings.ajax.isHeaderNotSend = false;
	};

	// envios
	this.sendAjax = function(data,url,funcs, set = {}){
		if(this.settings.ajax.isHeaderNotSend){ 
			this.sendAjaxHeader();
		}

		if(this.settings.ajax.executing && this.settings.ajax.blockIsExecuting){
			return; 
		}

		funcs.before ? funcs.before() : this.settings.ajax.defaultFuncs.before();
		let settings = Object.assign(this.settings.ajax.defults , set);
		// cl(set, this.settings.ajax.defaults);	  
	  $.ajax(settings); 
	};
	// --------- end sendAjax


	// Notificaciones	

	this.setDefaultSetting = function(settings){
		this.settings.noti.defaults = Object.assign(this.settings.noti.defaults , settings)
	}

	this.notificacion = function( heading , mensaje , type = 'info' ){
		// ---
	  if( !isNaN(type) ){
	    type = type == 200 ? "success" : "error";
	  }

		let settings = Object.assign(this.settings.noti.defaults);

	  let  info = this.settings.noti.defaults;

	  $.toast({
	    heading   : heading,
	    text      : mensaje,
	    position  : info.position,
	    showHideTransition : info.showHideTransition, 
	    hideAfter : info.hideAfter,
	    hideEasing: info.hideEasing,
	    icon      : type,
	    stack: false
	  });
	};


} // end helper

let H = new Helper()
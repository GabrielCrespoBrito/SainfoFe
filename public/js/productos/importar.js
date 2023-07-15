function events()
{
  $(".send_info").on('click' , function(e){

    if( !confirm("Esta seguro que desea importar la información?") ){
      return false;
    }

    e.preventDefault();
    let grupos = $("[name=grupos]");
    let familias = $("[name=familias]");
    let marca = $("[name=marcas]");
    let productos = $("[name=productos]");
    let prov_clientes = $("[name=prov_clientes]");
    // let ventas_cab = $("[name=ventas_cab]");      
    let is_venta = true; $("#form-accion").data('isventa');

    if(
      is_venta == "1" ||
      grupos.prop('checked') ||
      familias.prop('checked') ||
      marca.prop('checked') ||
      productos.prop('checked') ||
      prov_clientes.prop('checked')
      ){      

      if( !$("[name=excel]").val()){
        notificaciones("Tiene que subir un archivo excell", 'error');
        return;
      }

      let checkboxs = [ grupos, familias, marca, productos, prov_clientes];
      let  formData = new FormData();

      if( is_venta  ){
        formData.append( 'ventas_cab' , 'ventas_cab' );
      }
      else {

        for (var i = 0; i < checkboxs.length; i++) {
          let ele = checkboxs[i];
          if( ele.prop('checked') ){
            console.log("is checked", ele.attr('name')  );
            formData.append( ele.attr('name') , ele.val() );

          }
        }

      }

      var excel = $("[name=excel]")[0].files[0];            
      $(".block_elemento").show();

      formData.append( 'excel' , excel );

      $.ajax({
        type : 'post',
        url  : url_send,  
        data : formData,
        processData: false,
        contentType: false,      
        success : function(data){
          
          notificaciones( 'Acción Exitosa' , 'success' );
          $(".block_elemento").hide();

          setInterval(function(e){
            $("#form-accion input").val();
          }, 1000);
        },
        error : function(data){ 
          let response = data.responseJSON;
          let errors = data.responseJSON.errors;

          // console.log(errors);
          // return;

          if( Array.isArray(errors) ){
            notificaciones(errors, "error" , '');
            $(".block_elemento").hide();
          } 
          else {
              notificaciones(data.responseJSON, "error"); 
              $(".block_elemento").hide(); 
          }
        },

        complete : function(data){
          console.log("complete" , data)
        }
      });  
    }

    else {
      notificaciones("Selecciona una opción", 'error');
    }
  });
}

init(events);
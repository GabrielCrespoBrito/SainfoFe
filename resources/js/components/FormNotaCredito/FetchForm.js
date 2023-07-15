import Helper from "../../Helper";

export default function FetchForm(url, data, callBackSuccess = null) {

  document.getElementById("load_screen").style.display = "block";

  const requestOptions = {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  };

  try {

    fetch(url, requestOptions)
    .then(async response => {
      if (response.ok) {        
        return response.json();
      }
      const validation = await response.json();
      // console.log('validation', validation);
      // let errors = Helper.ProcessErrorLaravelFormat(validation.errors);
      return validation;
  })    
      .then(response => {

        console.log("then2" , response )
        document.getElementById("load_screen").style.display = "none";

        if ( response.errors){
          Helper.ErrorMessageAjax(response.errors)
          return;
        }
       
        if( "success" in response ){
          if( response.success ){
            Helper.Notificacion.success('Acciòn Exitosa');
            window.$("#modalNC").modal('hide');
            window.$("#modalND").modal('hide');
            if(callBackSuccess){
              callBackSuccess();
            }
            return;
          }
          else {
            Helper.Notificacion.error( response.message );
            return;
          }
        }

        Helper.Notificacion.success('Acciòn Exitosa');
        window.$("#modalNC").modal('hide');
        window.$("#modalND").modal('hide');
      })
      .catch(response => {
        Helper.ErrorMessageAjax(response)
        document.getElementById("load_screen").style.display = "none";
      });
    // fetch
  } 
  catch (error) {
    console.log("error-catch", error )
  }
}
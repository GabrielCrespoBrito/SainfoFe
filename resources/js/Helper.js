import { isObject } from "lodash";

let Helper = {}


Helper.GetToday = () => {

  const today = new Date();
  const yyyy = today.getFullYear();
  let mm = today.getMonth() + 1; // Months start at 0!
  let dd = today.getDate();

  if (dd < 10) dd = '0' + dd;
  if (mm < 10) mm = '0' + mm;

  return yyyy + '-' + mm + '-' + dd;
}

Helper.IsObjEmpty = (value) => {

  return Object.keys(value).length === 0 && value.constructor === Object; // 👈 constructor check
}

Helper.SumInArray = (array, prop) => {

  let sum = 0;

  for (let index = 0; index < array.length; index++) {
    const element = array[index];
    sum += Number(element[prop]);
  }

  return sum;
}


Helper.ProcessErrorLaravelFormat = data => {
  let errors = [];

  for (prop in data) {
    for (let i = 0; i < data[prop].length; i++) {
      errors.push(data[prop][i]);
    }
  }

  return errors;
}



Helper.IterateErrors = (errors) => {
  let erros_arr = [];
  for (let prop in errors) {
    for (let i = 0; i < errors[prop].length; i++) {
      erros_arr.push(errors[prop][i]);
    }
  }
  Helper.Notificacion.error(erros_arr)
  return;
}

Helper.ErrorMessageAjax = (data) => {

  console.log("error", data);



  const errorShow = (errors) => {
    let erros_arr = [];
    for (prop in errors) {
      for (let i = 0; i < errors[prop].length; i++) {
        erros_arr.push(errors[prop][i]);
      }
    }
    Helper.Notificacion.error(erros_arr)
    return;
  }


  if (data.responseJSON) {
    let errors = data.responseJSON.errors;
    errorShow(errors);
  }


  if (data.statusText) {
    Helper.Notificacion.error(data.statusText)
    return;
  }

  if (isObject(data)) {
    errorShow(data)
    return;
  }


  Helper.Notificacion.error('Se ha Procucido un error')
  return;
}

// Notificaciòn

Helper.Notificacion = {
  success: (title, message = null) => {
    notificaciones(title, 'success', message);
  },
  error: (title, message = null) => {
    notificaciones(title, 'error', message);
  },
  info: (title, message = null) => {
    notificaciones(title, 'info', message);
  },
  info: (title, message = null) => {
    notificaciones(title, 'info', message);
  },
}

Helper.GetCsrfToken = () => document.querySelector("[name=csrf-token]").content;

export default Helper;

class Validator {

  errors = [];
  dataForm = [];
  success = false;
  data = null;

  constructor(data) {
    this.data = data;
  }

  getErrors() {
    return this.errors;
  }

  addError(error) {
    this.setErrors([...this.errors, error]);
  }

  setErrors(errors) {
    this.errors = errors;
    return this;
  }

  setSuccess(success) {
    return this.success = success;
  }

 isValid(){
  return this.success;
 }

  validate() {
    return true;
  }

  generateDataForm()
  {
  }

  setDataForm(dataForm) {
    this.dataForm = dataForm;
  }

  getDataForm() {
    this.generateDataForm();
    
    return this.dataForm;
  }
}

export default Validator;
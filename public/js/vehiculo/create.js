
function initDatePicker()
{
  let $this = $("[name=VehInsc]");
  let format = $this.data('format') || 'yyyy-mm-dd';
  $this.datepicker({
    autoclose : true,
    format : format,
    language: 'es',
  })

}

// Helper.init(initDatePicker);


console.log("js")
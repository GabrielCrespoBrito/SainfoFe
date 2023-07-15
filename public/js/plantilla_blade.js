  function select2_init()
  {
    // @t
    $('#cliente_documento').select2({
      placeholder: "buscar cliente",
      minimumInputLength: 2,
      ajax: {
        url: url_buscar_cliente_select2,
        dataType: 'json',
        data: function (params) {
          return {
            data: $.trim(params.term)
          };
        },
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

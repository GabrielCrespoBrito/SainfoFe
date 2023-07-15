H = Helper__;

function initDatable()
{
  let table = $('#datatable')
  window.table = table.DataTable({
    "pageLength": 10,
    "responsive" : true,
    "processing" : true,
    "serverSide" : true,
    "order": [[ 0, "desc" ]],
    "ajax": {
      "url" : table.attr('data-url'),
      "data": function (d) {
       return $.extend( {}, d, {
         "mes"   : $("[name=mes] option:selected").val(),        
         "local" : $("[name=local] option:selected").val(),        
         "estadoAlmacen" : $("[name=estadoAlmacen] option:selected").val(),        
       });
      }
    },
    // 
    "columns" : [      
      { data: 'nro_venta' },
      { data : 'CpaNume' },
      { data : 'TidCodi' },
      { data : 'CpaFCpa' },
      { data : 'PCNomb' },
      { data : 'connomb' },
      { data : 'User_Crea' },
      { data : 'monnomb' },
      { data : 'CpaImpo' },
      { data : 'CpaPago' },
      { data : 'CpaSald' },       
      { data : 'CpaSdCa' },       
      { data : 'alm' },
      { data : 'accion' },
    ]
  });
}

H.add_events(function(){
  initDatable();
})

H.init();
Helper.init();
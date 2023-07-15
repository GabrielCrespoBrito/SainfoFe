H = Helper__;

function insertInfoHtml()
{

}

function showInfo()
{
  $("#modalShow").modal();
}

function initDatable()
{
  let table = $('#tableOrden')
  window.table = table.DataTable({
    "pageLength": 10,
    "responsive" : true,
    "processing" : true,
    'searching': false,
    "serverSide" : true,
    "order": [[ 0, "desc" ]],
    "ajax": {
      "url" : table.attr('data-url'),
      "data": function (d) {
       return $.extend( {}, d, {
         "status"   : $("[name=status] option:selected").val(),        
        //  "local" : $("[name=local] option:selected").val(),        
        //  "estadoAlmacen" : $("[name=estadoAlmacen] option:selected").val(),        
       });
      }
    },
    // 
    "columns" : [      
      { data: 'link' },
      { data : 'post_date' },
      { data : 'stat.num_items_sold' }, // Cantidad de items
      { data : 'stat.cliente.username' }, // Cliente Nombre+Email
      { data: 'documento', 'searchable': false, 'orderable': false }, // Clieexitnte Email
      { data: 'stat.cliente.email', 'searchable': false, 'orderable': false }, // Formulario
      { data: 'tlf', 'searchable': false , 'orderable' : false }, // Formulario
      { data : 'status' , 'searchable': false , 'orderable' : false },
      { data : 'accion' , 'searchable': false , 'orderable' : false },
    ]
  });
}


// Cliente' , 'Email', 'Formulario
  // @component('components.table', [ 'id' => 'datatable' , 'url' => route('tienda.orden.search')  , 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ '#' , 'Fecha' , 'Titulo' , 'Cli. Nombre' , 'Ruc', 'Email', 'Telefono', 'Estatus' ,''] ])
  // 

H.add_events(function(){
  initDatable();
  // $("#tableOrden").on('click', '.show-ele', showInfo )
})

H.init();
Helper.init();
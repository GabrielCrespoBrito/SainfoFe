// function initDatatable(){
//   table = $('#datatable-productos').DataTable({
//     autoFill: true,
//     language : {
//       processing: "Buscando...",
//       paginate: {
//         first:      "Primera",
//         previous:   "Anterior",
//         next:       "Siguiente",
//         last:       "Ultima"
//       }
//     }, 
//     "processing"   : true,
//     "serverSide"   : true,
//     "lengthChange" : false,
//     "ordering"     : false,  
//     "ajax": url_consulta,
//     "oLanguage": {"sSearch": "", "sLengthMenu": "_MENU_" },
//     "initComplete" : function initComplete(settings, json){         
//       $('div.dataTables_filter input').attr('placeholder', 'Buscar');
//     },
//     "columns" : [
//       { data : 'column1' },
//       { data : 'colmn2.name' },
//       // { data : 'ProNomb' , className : 'nombre_producto' , 'render' : function(){} , 'defaultContent' : '' },
//     ]
//   });
// }

function function_event()
{
	
}

function events()
{
  $(".ele").on('click' , function_event );

  // eventos_predeterminados('table_select_tr' , '#datatable' ) 
  // eventos_predeterminados('table_datatable' , '#datatable' )   
}

console.log("events",events, init);


// Iniciadora
init(
  events
)




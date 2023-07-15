
function agregar_producto(data)
{
  let data_productos = data.data_productos;
  // total_precio
  $(".total_cantidad").val(fixedNumber(data.totales.total_cantidad)) 
  $(".ultimo_costo").val(fixedNumber(data.totales.ultimo_costo));
  $(".costo_promedio").val(fixedNumber(data.totales.costo_promedio));
// total_precio
  function tdCreate( value)
  {
    return $("<td>" + value +  "</td>");
  }

  let tbody = $( "table tbody");
  tbody.empty();

  for (var i = 0; i < data_productos.length ; i++ ) {
    
    let d = data_productos[i];

    let tr = $("<tr></tr>");

    let enlaceDocumento =  `<a target="_blank" href="${d.route}"> ${d.correlativo} </a>`;
    let cliente = d.cliente_documento + ' ' +  d.cliente_nombre;

    let datos = [
      tdCreate( d.fecha ), 
      tdCreate( d.tipo_documento ),
      tdCreate( enlaceDocumento ), 
      tdCreate( cliente) ,
      tdCreate( d.unidad ),        
      tdCreate( d.cantidad ),
      tdCreate( d.moneda_abbreviatura ),
      tdCreate( d.precio ),
    ];

    tr
    .append(datos)
    .appendTo(tbody);
  }
}

function poner_data_producto(data)
{
  $(".codigo").val(data.producto.ProCodi);
  $(".nombre").val(data.producto.ProNomb);
  $("#modalSelectProducto").modal("hide");
  // console.log("poner data producto",data);
}


function show_table_search(id){

  table
  .search(id)
  .draw();  

  select_first_ele("#datatable-productos")

  $("#modalSelectProducto").modal("show");
}


function initDatatable(){

    table = $('#datatable-productos').DataTable({
      autoFill: true,
      language : {
        processing: "Buscando productos...",
        paginate: {
          first:      "Primera",
          previous:   "Anterior",
          next:       "Siguiente",
          last:       "Ultima"
        }
      },
      "processing"   : true,
      "serverSide"   : true,
      "lengthChange" : false,
      "ordering"     : false,  
      "ajax": url_route_productos_consulta,
      "oLanguage": {"sSearch": "", "sLengthMenu": "_MENU_" },
      "initComplete" : function initComplete(settings, json){         
        $('div.dataTables_filter input').attr('placeholder', 'Buscar producto');
      },
      "columns" : [
        { data : 'ProCodi' },
        { data : 'unpcodi' },
        { data : 'ProNomb' , className : 'nombre_producto' },
        { data : 'marca.MarNomb' , search : false },        
      ]
    });

}

function buscar_producto(id){

  let data = {
    codigo : id,
  };        
  let funcs = {
    success: poner_data_producto,
    error : function(){
      show_table_search(id);
    }
  };
  ajaxs( data , url_buscar_producto_datos , funcs  ); 
}


function producto_noexiste(data)
{
  notificaciones(data.responseJSON.data , 'error');
}

function search_producto(e)
{
  e.preventDefault();

  if( verifiy_producto()){      
    let data = {
      id_producto : get_value("[name=codigo]"),
      fecha_desde : get_value("[name=fecha_desde]"),
      fecha_hasta : get_value("[name=fecha_hasta]"),      
      condicion   : get_value("[name=condicion_articulo]")
    }

    $("#load_screen").show()

    let funcs = {
      success : agregar_producto,
      complete : function(){
        $("#load_screen").hide();
      }
    }

    ajaxs( data , url_buscar_producto , funcs );
    return false;
  }
  
  noti_focus( ".codigo" , "Tiene que seleccionar un producto" , "error" );
}

function verifiy_producto()
{
  return $(".codigo").val() != "";
}


function verificar_producto(e){

  if( e.keyCode == 13 ){
    buscar_producto( this.value );
  }
}


function seleccionar_elemento()
{

  let tr_select = $("#datatable-productos tbody tr.select");    

  console.log("tr_select",tr_select);

  if( tr_select.length  ){
    let id_producto = tr_select.find("td:eq(0)").text();
    buscar_producto( id_producto );
  }
}


  function teclado_acciones(e)
  {
    // 40 => [hacia abajo]
    // 38 => [hacia arriba]
    // 30 => [enter]

    let keyCode =  e.keyCode;

    // Subir o bajar
    if( keyCode === 40 || keyCode === 38 ){          
      console.log("teclado_acciones" , keyCode );
      table_select_up_down( "#datatable-productos" , keyCode );
      return false;
    }

    else if( keyCode === 13 ){   

      if(  $("#modalSelectProducto").is('.in') && $("#modalSelectProducto").find('.select').length  ){                
        let id = $("#modalSelectProducto .select").find('td:eq(0)').text();
        let nombre = $("#modalSelectProducto .select").find('td:eq(2)').text();

        $(".codigo").val(id);
        $(".nombre").val(nombre);
        $("#modalSelectProducto").modal("hide");
        $(".buscar").focus();
        return false;

      }
    }
  }


// events
function events()
{
  console.log( "aaaaa" );

  $("*").on("keydown" , teclado_acciones );
  $(".buscar").on('click' , search_producto );
  $(".codigo,.nombre").on('keyup' , verificar_producto )

  $(".elegir_elemento").on('click' , seleccionar_elemento )  
  $("#modalSelectProducto").on('shown.bs.modal' , function(){
    $("#datatable-productos_filter input").focus();
  });


  table.on('draw.dt' , function(){
    select_first_ele("#datatable-productos")
  })

  $("#datatable-productos").on( 'click' , "tbody tr" , function(e){ 
    table_select_elements(this,false,"select");
    // if( funcion_eje ) funcion_eje(this);
  });
  

  // table_select_elements("#datatable-productos");
}

console.log("events",events, init);

init(  
  datepicker,
  initDatatable,
  events
)




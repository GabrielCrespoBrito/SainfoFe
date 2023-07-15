$(document).on('ready', () => {

  $("body").on('change', '[name=order-status]',  e => {
    showTableOrders();
  })

  $("body").on('click', '.reloadTable', e => {
    e.preventDefault();
    showTableOrders();
  })

  $("body").on('click', '.btn-show-order', e => {

    e.preventDefault();
    const $ele = $(e.target);
    const info = $ele.data('info');
    const $eleTarget = $($ele.attr('data-target-container'));

    const getInput = (label, text, className = '') => {
      return `
      <div class="form-group ${className}">
        <label>${label}</label>
        <p class="form-control"> ${text} </p>
      </div>
      `
    }


    const getItems = () => {

      let items = ``;
      
      for (let index = 0, indexBase1 = 1; index < info.items.length; indexBase1++, index++) {
        const element = info.items[index];
        items += `
          <tr>
            <td>${indexBase1}</td>
            <td>${element.sku}</td>
            <td>${element.name}</td>
            <td>${element.quantity}</td>
          </tr>
        `;      
      }
      
      return items;
    };

    const classEstado = info.estado.completado ? ' col-md-4 estado-order-completado' : ' col-md-4 estado-order-pendiente'; 
    // Order
    $orderAppend = `
    <div class="container-order">
     <div class="cont">

      <div class="row">
        <div class="col-md-12"> <p class="title-order"> Orden Info </p> </div>
      </div>

      <div class="row"> 
        ${getInput('Nro', info.id, 'col-md-4')}
        ${getInput('Fecha ', info.created_at, 'col-md-4')}
        ${getInput('Estado ', info.estado.text, classEstado)}
        ${getInput('Mensaje ', info.mensaje, 'col-md-12'  )}
      </div>
    
      <div class="row">
        <div class="col-md-12"> <p class="title-order"> Cliente </p> </div>
      </div>

      <div class="row">
        ${getInput('Documento', info.cliente.documento, 'col-md-2')}
        ${getInput('Razón Social ', info.cliente.razon_social, 'col-md-4')}
        ${getInput('Email ', info.cliente.email, 'col-md-3')}
        ${getInput('Telefono ', info.cliente.telefono, 'col-md-3')}
      </div>
    
      <div class="row">
        <div class="col-md-12"> <p class="title-order"> Productos </p> </div>
      </div>

      <div class="row">
        <div class="col-md-12"> 
          <table class="table table-responsive table-orders sainfo-table">
            <thead>
            <tr>
            <td>Item </td> 
            <td>Codigo </td> 
            <td>Descripción </td> 
            <td>Cantidad </td> 
            </tr>
            </thead>
            ${getItems()}
          </table>
        </div>
      </div>

      </div>
      </div>
    `;

    $eleTarget.hide()

    $eleTarget.show(500, () => 
    $eleTarget
    .empty()
    .append($orderAppend));

  })
  

  // Si es search, hacer busqueda
  if ($("#containerOrders").attr('data-search') == 1) {
    console.log("Hacer busqueda por defecto");

    showTableOrders();
  }


  $("#modalImportTienda").on('shown.bs.modal', () => {

    console.log("modalImportTienda");

    if ($("#containerOrders").attr('data-load') == "0") {
      showTableOrders();
    }
  })


  // $("#modalShowOrden").on('shown.bs.modal', () => {
  //   // console.log("modalShowOrden");
  //   if ($("#containerOrders").attr('data-load') == "0") {
  //     showTableOrders();
  //   }
  // })

  

function showTableOrders()
{
  $("#load_screen").show();

  $container = $("#containerOrders");
  statusOrder = $container.find("[name=order-status] option:selected").val()
  fecha_desde = null,
  fecha_hasta = null,
  search = null;
  $container.empty();
  
  const data = {
    importMode: $("#containerOrders").attr('data-import'),
    status: statusOrder,
    fecha_desde: fecha_desde,
    fecha_hasta: fecha_hasta,
    search: search,
  };
  
  const url = $container.attr('data-url');
  
  ajaxs(data, url, {
    success : html => {
      $container.attr('data-load', "1");
      $container.append(html);
    },
    complete: data => $("#load_screen").hide()
  })
}






})
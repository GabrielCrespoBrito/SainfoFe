function iniciar_grafica(labels, data, reload = false) {

  var randomScalingFactor = function () { return Math.round(Math.random() * 100) };

  let options = {
    labels: labels,
    datasets: [
      {
        data: data,
      },
    ],
    ///
    options: {
      title: {
        display: true,
        text: 'Cust Chart Title'
      }
    }
  }

  var ctx = document.getElementById("canvas_importe").getContext("2d");
  var ctx2 = document.getElementById("canvas_importe2").getContext("2d");
  window.myBar = new Chart(ctx).Bar(options, { responsive: true });
  window.myBar2 = new Chart(ctx2).Bar(options, { responsive: true });

  let barLength = myBar.datasets[0].bars.length;

  for (let i = 0; i < barLength; i++) {
    myBar.datasets[0].bars[i].fillColor = "rgba(132,203,220,1)";
  }

  myBar.update();
}




function createSlotsData(docs)
{
  // const d = JSON.parse(window.data_grafica);
  const d = window.data_grafica;

  $("#data-dashboard").empty()

  // const docs = d.docs;
  const mescodi = $("[name=mes]").val();
  const dataEleParent = document.getElementById("data-dashboard")
  let routeGuiaEnviado = dataEleParent.dataset.guiaenviado
  let routeGuiaPorEnviar = dataEleParent.dataset.guiaporenviar
  let routeGuiaNoAceptadas = dataEleParent.dataset.guianoaceptadas
  let routeVentaEnviada = dataEleParent.dataset.ventaenviada
  let routeVentaPorEnviar = dataEleParent.dataset.ventaporenviar
  let routeVentaNoAceptadas = dataEleParent.dataset.ventanoaceptadas

  console.log(docs)


  for (const prop in docs) {

    if (prop == 52 || prop == "total") {
      continue;
    }
    
    const NOMBRES = {
      '01': 'FACTURA',
      '03': 'BOLETA DE VENTA',
      '07': 'NOTA DE CREDITO',
      '08': 'NOTA DE DEBITO',
      '09': 'GUIA DE REMISION'
    }
    
    const ele = docs[prop];

    console.log( prop, ele )
// 
    
    const nombre = NOMBRES[prop];

    if (prop == "09") {
      var routeNameNoAceptadas = routeGuiaEnviado.replace('LL', mescodi);
      var routeNamePorEnviar = routeGuiaPorEnviar.replace('LL', mescodi);
      var routeNameEnviado = "#";
    }
    else {
      var routeNameEnviado = routeVentaEnviada
        .replace('tipo_', prop)
        .replace('mes_', mescodi);
      var routeNameNoAceptadas = routeVentaNoAceptadas
        .replace('tipo_', prop)
        .replace('mes_', mescodi);
      var routeNamePorEnviar = routeVentaPorEnviar
        .replace('tipo_', prop)
        .replace('mes_', mescodi);
    }


    let aceptadas = 0;
    let pendientes = 0;
    let rechazadas = 0;
    let total = 0;
    
    if( prop == "09" ){
      aceptadas = ele.enviadas;
      pendientes = ele.por_enviar;
      rechazadas = ele.no_aceptadas;
      total = ele.total;
    }
    else {
      aceptadas = ele['0001'].cantidad;
      pendientes = ele['0011'].cantidad;
      rechazadas = ele['0002'].cantidad;
      total = ele.total.cantidad
    }

    // <div class="col-lg-2 col-xs-6">

    const dataInfo = `
    <div class="data-column">
    <div class="small-box" data-id="${prop}">
    <div class="inner">

    <h3 class="data total">
    <span class="data_total"> ${total}</span> <small>totales</small>
    </h3>
        <div class="row">

          <div class="data_documento col-md-12"> <span class="data enviadas">
            <a target="_blank" href="${routeNameEnviado}">
              <span class="value"> ${aceptadas}</span> enviadas
              </a>
          </div>
          
          <div class="data_documento col-md-12"> <span class="data no_enviadas">
          <a target="_blank" href="${routeNamePorEnviar}">
          <span class="value"> ${pendientes} </span>  por enviar
          </a>
          </div>
          
          <div class="data_documento col-md-12"> <span class="data no_aceptadas">
          <a target="_blank" href="${routeNameNoAceptadas}">
          <span class="value"> ${rechazadas}</span>  no aceptadas
          </a>
          </div>
          </div>
          
          <p> ${nombre} </p>
          <div class="icon">
          <i class="fa fa-file-o"></i>
          </div>
          
          
          </div>
          
          </div>
        </div>
          `

    $("#data-dashboard").append(dataInfo)
  }
  return;
}


function createGrafica(tipo, color = "rgba(0,0,0,.5)")
{
  const eles = {
    ventas : {
      container: $('.container-graphic-ventas'),
      title: $('#title-graphic-ventas'),
      titleText: 'Ventas',
      canvasId: 'graphic-ventas',
    },
    compras : {
      container: $('.container-graphic-compras'),
      title: $('#title-graphic-compras'),
      titleText: 'Compras',
      canvasId: 'graphic-compras',
    }
  }

  const ele = eles[tipo];
  const moneda = ele.container.parents('.container-grafica-parent').find('.btn-currency-change.active').attr('data-currency')
  const registers = window.data_grafica[tipo];  
  let registerValues = [];
  let total = 0;

  for (const dia in registers) {
    let value = registers[dia][moneda];
    total = total + Number(value);
    registerValues.push(value.toFixed(2));
  }

  total = total.toFixed(2);

  const title = `${ele.titleText} (${total})`
  ele.title.find('.cifra').text(title);

  ele.container
    .empty()
    .append(`<canvas height="100px" id="${ele.canvasId}"></canvas>`  )

    function getOptions(label, data) {
      return {
        labels: label,
        datasets: [{ data: data, fillColor : color }],
        options: { title: { display: true }}
      }
    }
    

  const labels = Object.keys(registers);
  new Chart(document.getElementById(ele.canvasId).getContext("2d"))
  .Bar(
    getOptions(labels, registerValues),
    { responsive: true }
  );  
}

function set_data_mes(data)
{
  // window.data_grafica = JSON.parse(data.data);
  window.data_grafica = data.data;
  // console.log('xxx');
  // console.log(data.data.docs);

  createSlotsData(data.data.docs);
  createGrafica('ventas', "rgba(60,141,188,1)" )
  createGrafica('compras')
}

function search_month_date() {
  let value = $(this).val();

  let currentDate = $(".dashboard .small-box a").each(function () {
    let $t = $(this);
    let href = $t.attr('href')
    let currentDate = href.slice(-6);
    let newDate = href.replace(currentDate, value);
    $t.attr('href', newDate);
  })

  ajaxs(
    { date: value },
    url_data_mes,
    { success: searchDataMensual }
  )

  return false;
}



function changeCurrency() {
  
  let $this = $(this);

  if( $this.is('.active') ){
    return;
  }
  
  $this.parents('.title-graphic').find('.btn-currency-change').removeClass('active');
  $this.addClass('active');

  const color = $this.attr('data-target') == 'ventas' ? 'rgba(60,141,188,1)' : null;
  createGrafica( $this.attr('data-target'), color )
}


function events() {
  $(".mes").on('change', searchDataMensual)
  $(".btn-currency-change").on('click', changeCurrency)
}

function searchDataMensual() {

  let value = $("[name=mes]").val();
  ajaxs(
    { date: value },
    url_data_mes,
    { success: set_data_mes }
  )

  return false;
}

init(
  events,
  searchDataMensual
  // iniciar_grafica.bind(this, window.data_grafica.labels, window.data_grafica.data)
)

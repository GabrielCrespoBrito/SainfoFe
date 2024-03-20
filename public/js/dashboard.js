window.needUsage = 1;

function iniciar_grafica(labels, data, reload = false) {


  // Funciones callbacck

  var myLegendContainer = document.getElementById("legend");
  // generate HTML legend
  myLegendContainer.innerHTML = chart.generateLegend();
  // bind onClick event to all LI-tags of the legend
  var legendItems = myLegendContainer.getElementsByTagName('li');
  for (var i = 0; i < legendItems.length; i += 1) {
    legendItems[i].addEventListener("click", legendClickCallback, false);
  }

  function legendClickCallback(event) {
    event = event || window.event;

    var target = event.target || event.srcElement;
    while (target.nodeName !== 'LI') {
      target = target.parentElement;
    }
    var parent = target.parentElement;
    var chartId = parseInt(parent.classList[0].split("-")[0], 10);
    var chart = Chart.instances[chartId];
    var index = Array.prototype.slice.call(parent.children).indexOf(target);
    var meta = chart.getDatasetMeta(0);
    var item = meta.data[index];

    if (item.hidden === null || item.hidden === false) {
      item.hidden = true;
      target.classList.add('hidden');
    } else {
      target.classList.remove('hidden');
      item.hidden = null;
    }
    chart.update();
  }



  //


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




function createSlotsData(docs) {
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

    if (prop == "09") {
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


function createGraficaStatus(totales) {

  $(".container-graphic-status")
    .empty()
    .append(`<canvas id="graphic-status"></canvas>`)


  // Obtén el contexto del canvas
  var ctx = document.getElementById('graphic-status').getContext('2d');

  let dataInfo = {

    '0001': {
      color: "#77e892",
      label: "Aceptado"
    },
    '0002': {
      color: "#edcf8c",
      label: "Rechazado"
    },
    '0003': {
      color: "rgb(255,0,0)",
      label: "Anulado"
    },

    '0011': {
      color: "#c7cbc8",
      label: "Pendiente"
    }

  }

  // let totales = data.docs.total;

  let labels = [];
  let values = [];
  let backGrounds = [];

  for (prop in totales) {
    if (prop != "total") {
      let cantidad = totales[prop].cantidad;
      if (cantidad) {
        values.push(totales[prop].cantidad);
        backGrounds.push(dataInfo[prop].color)
        labels.push(`(${totales[prop].cantidad}) ${dataInfo[prop].label}`);
      }

    }
  }

  const config = {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        label: 'Estados de Documentos',
        data: values,
        backgroundColor: backGrounds,
      }]
    },
    options: {
      plugins: {
        title: {
          display: true,
          text: 'Estado de Documentos '
        }
      }
    }
  };


  const myChart = new Chart(ctx, config);
  // myChart.resize(400, 300);
}


function createGraficaUso(usos) {

  console.log(usos)

  $(".container-graphic-ussage")
    .empty()
    .append(`<canvas id="graphic-ussage"></canvas>`)

  // Obtén el contexto del canvas
  var ctx = document.getElementById('graphic-ussage').getContext('2d');

  let valueUsado = 0;
  let valueDisponible = 0;
  
  
  for (prop in usos) {
    const uso = usos[prop];
    
    console.log(uso);

    if (uso.caracteristica.codigo == "comprobantes" ){
      valueUsado = uso.limite - uso.restante;
      valueDisponible = uso.restante; 
      break;
    }
  }

  console.log(valueUsado, valueDisponible)

  let dataGrafico = {
    usado: {
      value: valueUsado,
      color: "rgb(208, 208, 208)",
      label: `(${valueUsado}) Usado`
    },

    disponible: {
      value: valueDisponible,
      color: "rgb(118, 211, 125)",
      label: `(${valueDisponible}) Disponible"`
    },
  }

  const config = {
    type: 'doughnut',
    data: {
      labels: [dataGrafico.usado.label, dataGrafico.disponible.label],
      datasets: [{
        label: 'Documentos',
        data: [dataGrafico.usado.value, dataGrafico.disponible.value],
        backgroundColor: [dataGrafico.usado.color, dataGrafico.disponible.color],
        hoverOffset: 4
      }]
    },
    options: {
      plugins: {
        title: {
          display: true,
          text: 'Documentos Realizados '
        }
      }
    }
  };


  const myChart = new Chart(ctx, config);
}


function createGrafica(tipo, color = "rgba(0,0,0,.5)") {

  let values = [];
  let labels = [];
  let backGrounds = [];

  const eles = {
    ventas: {
      container: $('.container-graphic-ventas'),
      title: $('#title-graphic-ventas'),
      titleText: 'Ventas',
      canvasId: 'graphic-ventas',
    },
    compras: {
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
    // registerValues.push(value.toFixed(2));
    backGrounds.push(color);
    values.push(value.toFixed(2));
  }

  total = total.toFixed(2);

  const title = `${ele.titleText} (${total})`
  // ele.title.find('.cifra').text(title);

  ele.container
    .empty()
    .append(`<canvas id="${ele.canvasId}"></canvas>`)

  labels = Object.keys(registers);

  const ctx = document.getElementById(ele.canvasId).getContext("2d");

  const config = {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: title,
        data: values,
        backgroundColor: backGrounds,
      }]
    },
    options: {
      responsive: true
    }
  };


  let myChart = new Chart(ctx, config);
}

function set_data_mes(data) {

  
  window.data_grafica = data.data;
  
  createSlotsData(data.data.docs);
  createGrafica('ventas', "rgba(60,141,188,1)")
  createGrafica('compras');
  createGraficaStatus(data.data.docs.total)

  if (window.needUsage){
    createGraficaUso(data.usages);
  }

  window.needUsage = 0;
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


  ajaxs({
      date: value,
      needUsage: window.needUsage
    },
    url_data_mes,
    { success: searchDataMensual }
  )

  return false;
}



function changeCurrency() {

  let $this = $(this);

  if ($this.is('.active')) {
    return;
  }

  $this.parents('.title-graphic').find('.btn-currency-change').removeClass('active');
  $this.addClass('active');

  const color = $this.attr('data-target') == 'ventas' ? 'rgba(60,141,188,1)' : null;
  createGrafica($this.attr('data-target'), color)
}


function events() {
  $(".mes").on('change', searchDataMensual)
  $(".btn-currency-change").on('click', changeCurrency)
}

function searchDataMensual() {

  let value = $("[name=mes]").val();

  ajaxs({ 
    date: value,
    needUsage : window.needUsage 
  },
    url_data_mes,
    { 
      success: set_data_mes 
    }
  )

  return false;
}

init(
  events,
  searchDataMensual
)

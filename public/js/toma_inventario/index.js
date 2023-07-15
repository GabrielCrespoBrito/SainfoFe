H = Helper__;

function initDatable()
{
  let table = $('#datatable');

  // table.DataTable({
  //   "pageLength": 10,
  //   "responsive": true,
  //   "searching": true,
  //   "paging": false,
  //   'info' : false,
  // });

  // return;
  window.table = table.DataTable({
    "pageLength": 10,
    "responsive" : true,
    "searching": false,
    "paging": false,
    "info": false,
    "processing" : true,
    "serverSide" : true,
    "ajax": {
      "url" : table.attr('data-url'),
      "data": function (d) {
       return $.extend( {}, d, {
         "loccodi" : $("[name=local] option:selected").val(),        
       });
      }
    },
    // 
    "columns" : [      
      { data: 'link' },
      { data: 'InvFech' },
      { data: 'estado' },
      { data: 'InvObse' },
      { data: 'accion' },
    ]
  });
}

H.add_events(function(){
  initDatable();
  
  $("[name=local]").on('change' , function(){
    let $link = $(".btn-create");
    let link_new = $link.attr('data-href').replace( 'replace' , $(this).find('option:selected').val() );
    $link.attr('href' , link_new )
  })
})

H.init();
Helper.init();
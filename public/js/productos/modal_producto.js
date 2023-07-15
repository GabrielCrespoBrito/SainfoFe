/**
 * AppModalProducto
 * 
 */

//
// seleccionar una opción por defecto al select
function select_value(select_name, value) {
  let select = $("[name=" + select_name + "]");
  select.find('opcion').prop('selected', false);
  select.find("option[value=" + value + "]").prop('selected', true)
}



function agregarASelect(data, select, name_codi, name_text, adicional_info = []) {
  let select_agregar = $("[name=" + select + "]");
  select_agregar.empty();

  for (let i = 0; i < data.length; i++) {
    let actual_data = data[i];

    let option = $("<option></option>")
      .attr('value', actual_data[name_codi])
      .text(actual_data[name_text]);

    if (adicional_info.length) {
      for (let i = 0; i < adicional_info.length; i++) {
        let info = adicional_info[i];
        option.attr(info[0], actual_data[info[1]]);
      }
    }
    select_agregar.append(option);
  }
}

let AppModalProducto = 
{
	parent : "#modalProducto",
	eles : {},
  elementsSelected: {},
  productsDef: {},
  data : {
    settings : {
      focusSearch: true,
      backdrop : false,
      checkBoxSelect: false,
    }
  },

	get_url : function(){    
		let url = this.eles.button_submit.data('url');
		return url.replace('XX' , this.id );
  },
  
  add_event_btn : function(func){
    this.eles.btn_accion.on('click' , func);
  },

  add_event_btn_close: function (func) {
    this.eles.btn_close.on('click', func);
  },




  get_selected_row : function(){
    let selected = this.eles.table.find('.select');
    return selected.length ? selected : false;
  },

	show : function(){

    let settings = {};

    if( this.data.settings.backdrop ){
      settings.backdrop = 'static';
    }

		$(this.parent).modal(settings);
  },
  
  search(value){
    this.eles.datatable.search(value)
    this.draw();
  },

  draw() {
    this.eles.datatable.draw();
  },


  showSearch : function(value){
    this.search(value)
    this.show();
	},

	hide : function(){
		$(this.parent).modal("hide");		
	},


  selectRemoveAllCheckBox : function()
  {
    let checked = this.checked;

    AppModalProducto.eles.table.find( 'tbody .input-item').each(function(index,dom){   

      let $this = $(dom);

      if (checked == dom.checked ){
        return;
      }

      // Cambiar estado
      $this.prop('checked' , checked );

      AppModalProducto.addRemoveItemSelected(dom)
    });

  },

  selectRemoveCheckBox: function () 
  {
    // console.log("seleccionados", this );
    // console.log("seleccioandos def", AppModalProducto.elementsSelectedDef)

    AppModalProducto.addRemoveItemSelected(this)
  },

  addRemoveItemSelected : function( checkbox )
  {
    // Linguista
    let agregar = checkbox.checked;
    // console.log("checkbox", checkbox)
    let $tr = $(checkbox).parents('tr')
    let data = $tr.data('info');
    
    // Agregar
    if (agregar){
      $tr.addClass('selected');
      AppModalProducto.elementsSelected[data.ProCodi] = data;
    }

    // Eliminar
    else {
      delete AppModalProducto.elementsSelected[data.ProCodi];
      $tr.removeClass('selected');
    }
  },

	events : function(){

    if( this.data.settings.focusSearch ){

		  this.eles.table .on('shown.bs.modal' , function(){
        $(this).find(".dataTables_filter input").focus();
      });
    }

    // Cuando es checkbox
    if (this.data.settings.checkBoxSelect) {
      
      // Seleccionar todos los elementos
      this.eles.table.find('.input-select-all').on('change' , this.selectRemoveAllCheckBox )

      // Seleccionar elemento
      this.eles.table.on('change', '.input-item', this.selectRemoveCheckBox)

      // Al hacer la busqueda quitar la selección
      this.eles.table.on('draw.dt', function(){        
        // AppModalProducto.eles.table.find('.input-select-all').prop('checked', false )
      })

    }
    
    //
    $("body").on("change", ".select-field-producto", () => this.eles.datatable.draw());
    $("body").on("change", "[name=familia_filter],[name=marca_filter]", () => this.eles.datatable.draw());

    $("body").on("change", "[name=grupo_filter]", () => {

      // 
      const $grupoSelected = this.eles.modal.find("[name=grupo_filter] option:selected");
      const id_grupo = $grupoSelected.val();

      if(id_grupo == '' || id_grupo == null){
        $("[name=familia_filter]").empty();
        this.eles.datatable.draw()
        return;
      }


      let familias = $grupoSelected.data('familias')
      if (familias.length) {
        agregarASelect(familias, "familia_filter", "famCodi", "famNomb");
        this.eles.datatable.draw()
      }      
      else {
          let data = {
          'id_grupo': id_grupo
        }

        // formData.append('id_grupo', id_grupo);
        let funcs = {
          success: (familias) => {
            if (familias.length) {
              agregarASelect(familias, "familia_filter", "famCodi", "famNomb");
              $grupoSelected.attr('data-familias', JSON.stringify(familias));
              select_value('familia_filter', null);
            }
            // recovery-stranger-things
            else {
              agregarASelect([familia_empty], "familia_filter", "famCodi", "famNomb");
            }
            this.eles.datatable.draw()
          },
        }

        ajaxs(data, $("[name=grupo_filter]").attr('data-url'), funcs);
        // ajaxs(formData, $("[name=grupo_filter]").attr('data-url'), funcs);
      }



      console.log("grupoSelected", $grupoSelected)
      // this.eles.modal
      // this.eles.datatable.draw();
    });


  }, 

  changeSearchSelect : function(type)
  {
    $( ".select-field-producto", this.parent.modalProducto).find('option[value=' + type + ']').prop('selected', true);
  },

	set_eles : function(){
    this.eles.table = $( "table" , this.parent);
    this.eles.datatable = null;
		this.eles.modal = $(this.parent);
    this.eles.title = $( ".modal-title" , this.parent);
    this.eles.btn_accion = $(".btn-accion", this.parent);
    this.eles.btn_close = $("[data-dismiss=modal]", this.parent);
  },

  init_settings: function () {
    if (this.eles.table.find('thead input').length) {
      this.data.settings.checkBoxSelect = true;
    }
  }, 

  sumStock: function(value,data,info){
    let total =
      Number(info.prosto1) + 
      Number(info.prosto2) + 
      Number(info.prosto3) + 
      Number(info.prosto4) + 
      Number(info.prosto5) + 
      Number(info.prosto6) + 
      Number(info.prosto7) + 
      Number(info.prosto8) + 
      Number(info.prosto9) + 
      Number(info.prosto10);
    return total;    
  },

  inputColumn : function(value,data,info)
  {
    return `<input type="checkbox" ${AppModalProducto.elementsSelected[info.ProCodi] ? 'checked' : ''} class="input-item" value="1" name="product">`
  },

  init_datatable : function(){

    let url = this.eles.table.attr('data-url');

    let columns = [
      { data: 'ProCodi', searchable: false },
      { data: 'unpcodi', searchable: false },
      { data: 'ProNomb', 'class': 'strong', searchable: false },
      { data: 'marca_.MarNomb', searchable: false },
      { data: 'ProPUCD', searchable: false, className: 'text-right' },
      { data: 'ProPUCS', searchable: false, className: 'text-right' },
      { data: 'ProMarg', searchable: false, className: 'text-right' },
      { data: 'ProPUVS', searchable: false, className: 'text-right' },
      { data: 'unpcodi', 'class' : 'text-right', searchable: false, render: this.sumStock },
    ];
    
    if(this.data.settings.checkBoxSelect){
      let columnInput =  {
        'data': 'input',
        'searchable': 'false',
        'render' : this.inputColumn
      } 
      columns.unshift( columnInput )
    }


    function fixedNumber(v, codigo = false) 
    {
      let n = v;
      if (isNaN(v)) {
        n = v;
      }
      n = codigo ? v : (Math.round(v * 100) / 100).toFixed(2);
      return n;
    }

    let cantidad_almacenes = $(this.eles.table).find('thead td.almacenes'); 
    for (let index = 0; index < cantidad_almacenes.length; index++) {
      let stock_number = $(cantidad_almacenes[index]).attr('data-id');
      let campo_id = 'prosto' + stock_number;
      // console.log( "campo_id", campo_id );
      columns.push({ data: campo_id, className: 'text-right', searchable: false });
    }
    
    columns.push({ data: 'ProPerc', className: 'text-right', searchable: false });
    columns.push({ data: 'ProPeso', className: 'text-right', searchable: false });
    columns.push({ data: 'BaseIGV', className: 'text-right', searchable: false });
    columns.push({ data: 'ISC', className: 'text-right', searchable: false });
    columns.push({ data: 'tiecodi', searchable: false });

    $( this.eles.table ).one("preInit.dt", function () {
      // loremp-ipsum-loremp-ipsum
      let $button = 
      $(`
        <select class='select-field-producto input-sm form-control'>
        <option value='codigo'>Codigo</option>
        <option value='nombre'>Nombre</option>
        <option value='codigo_barra'>Codigo de barra</option>
        </select>`);
      $("#DataTables_Table_0_filter label").prepend($button);
      $button.button();
    });

    this.eles.datatable = this.eles.table.DataTable({
      "processing" : true,
      "serverSide" : true,
      "lengthChange": false,
      "ordering": false,
      "paging": true,
      "ajax": {
        "url": url,
        "data": function (d) {
          return $.extend({}, d, {
            "campo_busqueda": $(".select-field-producto").val(),
            "grupo": $("[name=grupo_filter] option:selected").val(),
            "marca": $("[name=marca_filter] option:selected").val(),
            "familia": $("[name=familia_filter] option:selected").val()
          });
        }
      },
      "oLanguage": {"sSearch": "", "sLengthMenu": "_MENU_" },
      "initComplete" : function initComplete(settings, json){         
        $('div.dataTables_filter input').attr('placeholder', 'Buscar');
      },
      createdRow : function( row , data , index ) {
        let $row = $(row);
        $row.data('info', data );
        if (AppModalProducto.elementsSelected[data.ProCodi]){
          $row.addClass('selected');
        }
      },
      "columns" : columns
    });
  },

	init : function( ){
    this.set_eles();
    this.init_settings();
    this.init_datatable()
		this.events();
	},
};

export { AppModalProducto };
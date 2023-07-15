import { saludo, modalDeleteElements , initDataTable } from './include.js';

let Helper = {
  modalDeleteElements : modalDeleteElements,
	data : { modalDelete : "modalDelete" },
	ajaxs : {},
  events : {
  	registers : null,
  	defaults : function() {
      console.log( "this" , this );
  	}.bind(this),
  },
  init : function() {
  	arguments.map( (ele) => ele() );
  	this.data.events()
    this.events.defaults() 
  }
}

console.log(Helper);
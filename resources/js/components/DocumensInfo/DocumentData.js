import { React, Component } from 'react'
import DataSegment from './DataSegment';

class DocumentData extends Component {
  render() {
    const { data } = this.props;
    return (
      <div className="col-lg-2  col-xs-6">
        <div className="small-box action-user" data-id="01">
          <div className="inner">
            <h3 className="data total">
              <span className="data_total">{data.total}</span> <small>Totales</small>
            </h3>
            <div className="row">
              {data.items.map((item) => {                
                <DataSegment url={item.url} value={item.value} name={item.name} />
              })}
            </div>
            <p>{ data.name }</p>
            <div className="icon">
              <i className="fa fa-file-o"></i>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

export default DocumentData;


/*
<div class="col-lg-2  col-xs-6">
    <div class="small-box action-user" data-id="01">

  <div class="inner">

    <h3 class="data total"> 
      <span class="data_total">9</span> <small>totales</small> 
    </h3>

    <div class="row">

    <div class="data_documento col-md-12"> <span class="data enviadas">       
      <a target="_blank" href="http://10323013760.localhost:8000/ventas?status=0001&amp;tipo=01&amp;mes=202208">
        <span class="value">0</span> enviadas  
      </a>
    </span></div>

    <div class="data_documento col-md-12"> <span class="data no_enviadas action"> 
    <a target="_blank" href="http://10323013760.localhost:8000/ventas?status=0011&amp;tipo=01&amp;mes=202208">
      <span class="value"> 9 </span>  por enviar
    </a>
    </span></div>

 
    <div class="data_documento col-md-12"> <span class="data no_aceptadas "> 
    <a target="_blank" href="http://10323013760.localhost:8000/ventas?status=0002&amp;tipo=01&amp;mes=202208">
      <span class="value"> 0 </span>  no aceptadas
    </a>
    </span></div>

    </div>

    <p> Facturas </p>
      <div class="icon">
        <i class="fa fa-file-o"></i>
      </div>


  </div>

</div>  

</div>

*/
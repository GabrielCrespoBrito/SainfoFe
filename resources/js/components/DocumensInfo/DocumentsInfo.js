import {React, Component} from 'react'
import DocumentData from './DocumentData'

class DocumentsInfo extends Component 
{
  componentDidMount() {
  }

  static = {
    documents : [
      { total : 100, name: 'Facturas', items : [
        { url: '#', value: 10, name: 'No Enviadas' },
        { url: '#', value: 70, name: 'Enviadas' },
        { url: '#', value: 20, name: 'No Aceptadas' }
      ]},
      {
        total: 100, name: 'Boletas', items: [
          { url: '#', value: 10, name: 'No Enviadas' },
          { url: '#', value: 20, name: 'Enviadas' },
          { url: '#', value: 30, name: 'No Aceptadas' }
        ]
      },
    ]
  }

  render() {
    let { documents } = this.static;
    return (
      <div className="div-container">
        {documents.map((document) => {
          return <DocumentData data={document}></DocumentData>
      })}
      </div>      
    );

  }
}

export default DocumentsInfo;

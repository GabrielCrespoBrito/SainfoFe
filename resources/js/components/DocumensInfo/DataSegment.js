import { React, Component } from 'react'

class DataSegment  extends Component {
  render () {
    const className = this.props.name + " " + this.props.action ? 'action' : '';    
    return (
      <div className="data_documento col-md-12">
        <span className={className}>
          <a target="_blank" href={this.props.url}>
            <span className="value">{this.props.value}</span> {this.props.name}
          </a>
        </span>
      </div>
    )
  }
}

DataSegment.defaultProps = {
  url: '#',
  value: '0',
  name: 'no-name',
  action: false,
}

export default DataSegment;
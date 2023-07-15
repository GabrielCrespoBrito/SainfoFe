import React, { useEffect } from 'react';

function Modal (props) {

  const title = props.title;

  // console.log(props)
  return (
    <>
      <div 
        style={{ display : 'block' }}
        className='modal fade in' 
        id={props.id}
        data-backdrop={props.backdrop}
        data-keyboard={props.keyboard}>
        <div className="modal-dialog modal-md">
          <div className="modal-content">
            <div className="modal-header">
              <button 
                type="button"
                className="close"
                onClick={() => { props.setIsClose(false) }}>
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 className="modal-title">{props.title}</h4>
            </div>
            <div className="modal-body">
              {props.children}
            </div>   
          </div>
        </div>
      </div>
    </>
  )
}

Modal.defaultProps = {
  open: false,
  id: 'modalComponent',
  backdrop: 'false',
  title: '-',
  keyboard: 'true',
}

export default Modal;
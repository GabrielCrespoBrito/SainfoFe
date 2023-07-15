function Enlace(props) {
  return ( 
    <a
      href={props.href}
      onClick={props.onClick}
      className={props.className}>
        {props.children}
    </a>
   );
}

Enlace.defaultProps = {
  href: '',
  onClick: event => null ,
  className: '',
}

export default Enlace;
function Button(props) {
  return (
    <button
      type={props.type}
      onClick={props.onClick}
      className={props.className}>
      {props.children}
    </button>
  );
}

Button.defaultProps = {
  type: 'button',
  onClick: event => null,
  className: '',
}

export default Button;
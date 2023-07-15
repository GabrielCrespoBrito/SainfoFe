import React from 'react';


const URL = "/searchGrupos";

class GrupoSelect extends React.Component {

  state = {
    grupos: [],
    grupo_selected: {},
  }

  selectGrupo = React.createRef()
  selectFamilia = React.createRef()

  static defaultProps = {
    allSelection: false,
    className: 'col-md-12',
    classNameParentSelect: 'col-md-6',
  }

  handleChange = () => {
    let grupo = this.state.grupos.filter(grupo => grupo.GruCodi == this.selectGrupo.current.value);
    this.setState({
      grupo_selected: grupo[0]
    })
  }

  getAllSelectOption() {
    return {
      'GruCodi': 'todos',
      'GruEsta': '0',
      'GruNomb': '-- TODOS --',
      fams: [
        {
          'famCodi': 'todos',
          'famNomb': '-- TODOS --',
          'gruCodi': '-1',
          'empcodi': null,
        }
      ]
    };
  }


  componentDidMount() {
    fetch(URL)
      .then(res => res.json())
      .then(res => {

        if (this.props.allSelection) {
          res.unshift(this.getAllSelectOption());
        }

        this.setState({ grupos: res, grupo_selected: res[0] })
      })
      .catch(error => console.log("error response", error))
  }

  render() {

    return (
      <div className={this.props.className}>
        <div className={this.props.classNameParentSelect}>
          <select ref={this.selectGrupo}
            required
            onChange={this.handleChange}
            name="GruCodi"
            className="form-control input-sm flat text-center">
            {
              this.state.grupos.map(grupo =>
                <option
                  key={grupo.GruCodi}
                  value={grupo.GruCodi}>
                  {grupo.GruNomb}
                </option>
              )
            }
          </select>
        </div>

        <div className={this.props.classNameParentSelect}>
          <select
            required
            ref={this.selectFamilia}
            onChange={this.handleChange}
            name="FamCodi"
            className="form-control input-sm flat text-center">
            {
              this.state.grupos.length && (
                this.state.grupo_selected.fams.map(familia =>
                  <option
                    key={familia.famCodi}
                    value={familia.famCodi}>
                    {familia.famNomb}
                  </option>
                )
              )
            }
          </select>
        </div>
      </div>
    );
  }
}

export default GrupoSelect;
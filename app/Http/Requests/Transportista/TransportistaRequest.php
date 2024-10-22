<?php

namespace App\Http\Requests\Transportista;

use App\TipoDocumento;
use App\Transportista;
use App\Rules\RucValidation;
use Illuminate\Foundation\Http\FormRequest;

class   TransportistaRequest extends FormRequest
{
  public $isPost;
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $this->isPost = strtolower($this->getMethod()) == 'post';
    $tiposdocumento = implode(',', array_keys(TipoDocumento::TRANSPORTISTAS));
    $rules = [
      'Nombres' => 'required|max:90',
      'Apellidos' => 'required|max:90',
      'TDocCodi' => 'required|in:' . $tiposdocumento,
      'TraRucc' => ['required', 'numeric'],
      'TraDire' => 'nullable|max:90',
      'TraTele' => 'nullable|max:90',
      'TraLice' => ['required', 'alpha_num' , 'min:9', 'max:20'],
    ];
    return $rules;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {

        $empresa_trans = Transportista::where('TraRucc', $this->TraRucc)->first();
        if ($empresa_trans) {
          if ($this->isPost) {
            $validator->errors()->add('TraRucc', 'El Documento del transportista ya esta siendo usado');
            return;
          }

          $currentEmpresa = Transportista::findOrfail($this->route()->parameters()['transportistum']);
          if ($empresa_trans->id != $currentEmpresa->id) {
            $validator->errors()->add('TraRucc', 'El Documento del transportista ya esta siendo usado');
            return;
          }
        }
      });
    }
  }
}

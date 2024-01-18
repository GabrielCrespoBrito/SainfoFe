<?php

namespace App\Http\Requests;

use App\Zona;
use Illuminate\Foundation\Http\FormRequest;

class ZonaRequest extends FormRequest
{
  public $isStore;

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
    $this->isStore = $this->route()->getName() == 'zonas.store';

    $rules = [
      'ZonNomb' => 'required|max:155',
    ];
    
    if( $this->isStore ){
      $rules['ZonCodi'] = 'required|max:4';
    }

    return $rules;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {
      $validator->after(function ($validator) {


        if ($this->isStore) {
          $zona = Zona::find($this->ZonCodi);
          if ($zona) {
            $this->errors()->add('ZonCodi', "El Codigo {$this->ZonCodi} ya esta siendo usado");
          }
          return;
        }

        $zona = Zona::findOrfail($this->route()->parameters()['zona']);

        if (is_null($zona)) {
          $this->errors()->add('ZonCodi', "No Existe la zona que quiere modificar");
          return;
        }
      });
    }
  }
}

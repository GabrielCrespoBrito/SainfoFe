<?php

namespace App\Http\Requests;

use App\Zona;
use Illuminate\Foundation\Http\FormRequest;

class ZonaDestroyRequest extends FormRequest
{
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
        return [
            //
        ];
    }

  public function withValidator($validator)
  {
    // dd($this->route()->parameters());

    $validator->after(function ($validator) {

      $zona = Zona::findOrfail($this->route()->parameters()['zona']);


      if ($zona->isDefault()) {
        $validator->errors()->add('ZonCodi', 'No se puede eliminar la zona por defecto');
        return;
      }

      if ($zona->ventas->count()) {
        $validator->errors()->add('ZonCodi', 'No puede eliminarse esta zona, porque tiene ventas asociadas');
        return;
      }

      if ($zona->cotizaciones->count()) {
        $validator->errors()->add('ZonCodi', 'No puede eliminarse esta zona, porque tiene cotizaciones asociadas');
        return;
      }

      if ($zona->compras->count()) {
        $validator->errors()->add('ZonCodi', 'No puede eliminarse esta zona, porque tiene compras asociadas');
        return;
      }

      if ($zona->guias->count()) {
        $validator->errors()->add('ZonCodi', 'No puede eliminarse esta zona, porque tiene guias asociadas');
        return;
      }
    });
  }
}

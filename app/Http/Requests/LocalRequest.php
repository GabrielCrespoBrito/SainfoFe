<?php

namespace App\Http\Requests;

use App\Local;
use App\ListaPrecio;
use Illuminate\Foundation\Http\FormRequest;

class LocalRequest extends FormRequest
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

  public function prepareForValidation()
  {
    $this->isStore = $this->route()->getName() == "locales.store";

    $this->merge([
      'LocNomb' => strtoupper($this->LocNomb),
      'serie' => strtoupper($this->serie),
    ]);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $rules = [
      'LocNomb' => 'required|max:100',
      'LocDire' => 'required|max:190',
      'LocDist' => 'required|exists:ubigeo,ubicodi',
      'LocTele' => 'nullable|max:120',
      'PDFLocalNombreInd' => 'sometimes|in:0,1',
    ];

    if ($this->isStore) {
      $rules['serie'] = ['required', 'max:3', 'regex:/^[a-zA-Z0-9]+$/'];
      $rules['tidcodi_01'] = 'required|numeric|int|min:0';
      $rules['tidcodi_03'] = 'required|numeric|int|min:0';
      $rules['tidcodi_07-01'] = 'required|numeric|int|min:0';
      $rules['tidcodi_07-03'] = 'required|numeric|int|min:0';
      $rules['tidcodi_08-01'] = 'required|numeric|int|min:0';
      $rules['tidcodi_08-03'] = 'required|numeric|int|min:0';
      $rules['tidcodi_09'] = 'required|numeric|int|min:0';
      $rules['tidcodi_50'] = 'required|numeric|int|min:0';
      $rules['tidcodi_52'] = 'required|numeric|int|min:0';
      $rules['tidcodi_53'] = 'required|numeric|int|min:0';
      $rules['tidcodi_98'] = 'required|numeric|int|min:0';
      // Lista
      $rules['lista_nombre'] = 'required';
      $rules['lista_copy_id'] = 'required';
    }

    return $rules;
  }

  public function validateUsers(&$validator)
  {
    if (!$this->users) {
      return true;
    }

    if (!is_array($this->users)) {
      $validator->errors()->add('users', 'Los usuarios tiene que ser un listado');
      return false;
    }

    $users_difs = collect($this->users)->diff(get_empresa()->users->pluck('usucodi'))->count();

    if ($users_difs) {
      $validator->errors()->add('users', 'Hay Usuarios no pertecen a esta empresa');
      return false;
    }

    return true;
  }

  public function validateLista(&$validator)
  {
    $lista = ListaPrecio::where('LisNomb', $this->lista_nombre)->first();

    if ($lista) {
      $validator->errors()->add('LisNomb', "Ya se encuentra repetido este nombre");
      return false;
    }

    // Comprobar que la lista de precios a copiar exista
    if (ListaPrecio::find($this->lista_copy_id) == null) {
      $validator->errors()->add('LisCodi', "La lista de precio a copiar no existe");
      return false;
    }

    return true;
  }


  public function validateSerie(&$validator)
  {
    if (get_empresa()->serieExists($this->serie)) {
      $validator->errors()->add('serie', "La serie {$this->serie} ya esta registrada en otro local");
      return false;
    }

    return true;
  }

  public function withValidator($validator)
  {
    if (!$validator->fails()) {

      $validator->after(function ($validator) {

        $localByNombre = Local::where('LocNomb', $this->LocNomb)->first();

        if (!$this->validateUsers($validator)) {
          return;
        }

        # Creando
        if ($this->isStore) {

          // validar Nombre
          if ($localByNombre) {
            $validator->errors()->add('LocNomb', 'El nombre del local esta repetido');
            return;
          }

          // validar series
          if ($this->validateSerie($validator)) {
            return;
          }

          // validar lista de precios
          if ($this->validateLista($validator)) {
            return;
          }  

        }

        # Modificando
        else {


          if( is_null($localByNombre)){
            return;
          }

          $id = $this->route()->parameters['locale'];
          $local = Local::findOrfail( $id );

          if (  $localByNombre->id != $local->id ) {
            $validator->errors()->add('LocNomb', 'Ya hay un local con el mismo nombre');
            return;
          }
          
        }



      });
    }
  }
}

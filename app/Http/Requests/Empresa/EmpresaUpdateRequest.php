<?php

namespace App\Http\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaUpdateRequest extends FormRequest
{
  public $logos_dimenciones;


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
    $this->logos_dimenciones = config('app.logos_dimenciones');
  }


	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			"nombre_comercial" => "required|max:120",
			"direccion" => "required|max:250",
			"ubigeo" => "required|exists:ubigeo,ubicodi",
			// "departamento" => "required|max:45",
			// "provincia" => "required|max:45",
			// "distrito" => "required|max:45",
			"email" => "required|max:120",
			"telefonos" => "required|max:120",
			"rubro" => "nullable|max:200",
      // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
      // 'imprimir'      => 'sometimes|nullable|in:1',                                                                                                                                                                           |
      // 'nombre_impresora'  => 'required_if:imprimir,1|max:255',                                                                                                                                                                | 
      // 'cant_copias'      => 'required_if:imprimir,1|integer|min:0',                                                 
      // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
      
			"logo_principal" => "sometimes|mimes:jpeg,bmp,png|dimensions:max_width={$this->logos_dimenciones['a4']['width']},max_height={$this->logos_dimenciones['a4']['height']}",
			"logo_secundario" => "sometimes|mimes:jpeg,bmp,png|dimensions:max_width={$this->logos_dimenciones['ticket']['width']},max_height={$this->logos_dimenciones['ticket']['height']}",
			"logo_subtitulo" => "sometimes|mimes:jpeg,bmp,png|dimensions:max_width={$this->logos_dimenciones['subtitulo']['width']},max_height={$this->logos_dimenciones['subtitulo']['height']}",
			// "logo_subtitulo" => "sometimes|mimes:jpeg,bmp,png|dimensions:max_width={$this->logos_dimenciones['marca_agua']['width']},max_height={$this->logos_dimenciones['marca_agua']['height']}",
		];
	}

  public function messages()
  {
     return [
      'logo_principal.dimensions' => 'El logo no puede superar las dimenciones maximas de (:max_width x :max_height) pixeles.',
      'logo_secundario.dimensions' => 'El logo ticket no puede superar las dimenciones maximas de (:max_width x :max_height) pixeles.'
     ];
  }


}


/*

La única estrategia para la productividad y el éxito en la vida que funciona.
Imagen creada por el autor.

La cultura del ajetreo y los oradores motivadores pueden haberlo llevado a creer que debe tener todos los días perfectos.

Estoy muy en desacuerdo con ellos.
***********************************
No es el día perfecto lo que cuenta, sino cómo te presentas en los días malos.
------------------------------------------------------------------------------------------------
Tener días locos y productivos es genial si tu objetivo es flexionar en las redes sociales.

Pero de lo que se trata es de no descartar un día en el que te sientes terrible y seguir mostrándote como la persona que quieres ser.

Perseguir picos nunca te llevará a ninguna parte más que directamente a la rutina.

Pero aumentar su línea de base de forma lenta y constante es clave.

Las pequeñas ganancias no impresionan a los demás y no son nada de lo que pueda presumir.

Pero solo al enfocarse en las pequeñas victorias mejorará sus habilidades, desarrollará su competencia, resiliencia y se convertirá en la persona que desea ser.

En el pasado, como muchos otros, ahora estaba atrapado en un ciclo de 1. motivarme, 2. establecer expectativas locas y luego 3. fallar porque sobrestimé mis habilidades.

El resultado fue que caí en la depresión cada dos semanas.

Pero la solución fue simple y poco impresionante:

Centrándose por completo en conseguir las pequeñas ganancias y olvidándose de los “días perfectos”.

Un entrenamiento de 5 minutos en un día en el que te sientes como una mierda.

Leer 2 páginas cuando absolutamente no quieres.

Una sesión de meditación de 1 minuto cuando te sientes disperso, irritado y solo quieres disfrutar de la dopamina.

Tienes que obtener todas las pequeñas ganancias porque se componen.

Olvídese de perseguir picos y deje de seguir a todos los que digan lo contrario.

Este es el único camino que funciona.

Consigue una pequeña victoria hoy.

Tu entrenador de autodominio Max

*/
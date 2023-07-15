<?php

namespace App\Http\Requests\Resumen;

use App\Resumen;
use Illuminate\Foundation\Http\FormRequest;

class ResumenUpdateRequest extends FormRequest
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
            'fecha_generacion' => 'required|date',
            'fecha_documento' => 'required|date',
            'ticket' => 'required|numeric',
            'docnume'   => 'required',
            // 'xxx'   => 'required|numeric',
        ];
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {

            $validator->after(function ($validator) {

                $parameters = $this->route()->parameters();
                $docnume = $parameters['docnume'];
                $numoper = $parameters['numoper'];

                $resumen = Resumen::findMultiple( $numoper, $docnume );              
      
                if ( ! $resumen  ) {
                    $validator->errors()->add('field', 'El resumen no existe');
                    return;
                }

                if (! $resumen->canEdit() ) {
                    $validator->errors()->add('field', 'No se puede la información del resumen, bien sea por falta Validar el ticket, o porque ya se encuentra Aceptado');
                    return;
                }

            });


        }




    }


}

<?php

namespace App\Http\Requests\ModuloApi;

use App\Rules\ModuloApi\RucSearchDocument;
use App\Rules\ModuloApi\TokenValid;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class getStatusRequest extends FormRequest
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
        $rules =  [
            'xx' => 'required|in:01,07,08',
            'tipo_documento' => 'required|in:01,07,08',
            'serie' => 'required|in:01,07,08',
            'numero' => 'required|in:01,07,08',
        ];

        return $rules;
    }
}

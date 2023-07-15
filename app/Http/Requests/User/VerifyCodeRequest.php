<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest
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
			'code' => 'required|digits:4'
		];
	}

	public function withValidator($validator)
	{
		if (!$validator->fails()) {
			$validator->after(function ($validator) {
				if (! auth()->user()->isCorrectVerificationCode($this->code)) {
					$validator->errors()->add('code', 'El codigo de verificación es incorrecto');
					return;
				}
			});
		}
	}
}

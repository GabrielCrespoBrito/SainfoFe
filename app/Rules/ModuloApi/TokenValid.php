<?php

namespace App\Rules\ModuloApi;

use App\ModuloApi\Models\User\User;
use Illuminate\Contracts\Validation\Rule;

class TokenValid implements Rule
{
	public $message;
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		$user = User::getByToken($value);

		if (is_null($user)) {
			$this->message = 'El token suministrado es incorrecto.';
			return false;
		}

		return true;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return $this->message;
	}
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Recapcha implements Rule
{
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
        $recaptcha = new ReCaptcha( env('CAPTCHA_SECRET'));
        $response = $recaptcha->verify( $value , $_SECRET['REMOTE_ADDR'] );
        return $response->isSuccess();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Por favor complete el captcha para enviar el formulario';
    }
}

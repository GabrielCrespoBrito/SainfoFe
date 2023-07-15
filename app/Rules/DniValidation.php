<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DniValidation implements Rule
{
    public $message = "El dni suministrado no es valido";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $searchOnline = false;

    public function __construct($searchOnline = false)
    {
        $this->searchOnline = $searchOnline;
    }

    /**
     * Validar si es un ruc valido
     *
     * @param [type] $attribute
     * @param [type] $value
     * @return void
     */
    public function validate($valor)
    {
        $valorLen = strlen($valor);

        if (is_numeric($valor)) {

            if( $valorLen == 8 ){
                return true;
            }
        }

        return false;
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
        $isValid = $this->validate($value);

        if ($this->searchOnline && $isValid) {
            return $this->searchRuc($value);
        }

        return $isValid;
    }


    public function searchRuc($dni)
    {
        $consult = consultar_dni($dni);

        if ($consult['success']) {
            return true;
        }

        if (isset($consult['error'])) {
            $this->message = $consult['error'] ? $consult['error'] : $this->message();
        }

        return false;
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

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message();
    }
}
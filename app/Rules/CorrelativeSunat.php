<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CorrelativeSunat implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */


    # Tipo de documento
    public $type;

    # Expresion regular para comparar
    public $patter;
    
    const REG = [
        '01' => '/^[F][F1234567890]{3}-[0123456789]{1,8}/',
        '03' => '/^[B][B1234567890]{3}-[0123456789]{1,8}/',
    ];

    public function __construct($type)
    {
        $this->type = $type;
        $this->patter = self::REG[$type];
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
      return (bool) preg_match( $this->patter , strtoupper($value) );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->type == "01" ? 'El correlativo de la facturas tiene que ser parecido F001-2' : 'El correlativo de la boletas tiene que ser parecido B001-2';
    }
}

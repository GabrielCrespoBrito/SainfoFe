<?php
namespace App\ModuloApi\Models\Empresa;


use Illuminate\Database\Eloquent\Model;
use App\ModuloApi\Models\traits\UseApiConnection;

class Empresa extends Model
{
    use UseApiConnection;

    public static function findByRuc( $value )
    {
        return self::where('ruc' , $value)->first();
    }

    public function getUserSol()
    {
        return $this->ruc . $this->usuario_sol;
    }

    public function getPasswordSol()
    {
        return $this->clave_sol;
    }

    public function isValidEmpresa()
    {
        return true;
    }

}

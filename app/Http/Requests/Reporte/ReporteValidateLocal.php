<?php

namespace App\Http\Requests\Reporte;

use Illuminate\Foundation\Http\FormRequest;

class ReporteValidateLocal extends FormRequest
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
            //
        ];
    }

    public function getDiasLimite()
    {

    }

    public function withValidator($validator)
    {
      if(!$validator->fails()){
        $validator->after(function($validator){

        // $fecha_desde = $this->route()->parameters()['fecha_desde'];
        // $fecha_hasta = $this->route()->parameters()['fecha_hasta'];
        // $local = $this->route()->parameters()['local'];

        // $validator = validator([
        //   'fecha_desde' => $fecha_desde,
        //   'fecha_hasta' => $fecha_hasta,
        //   'local' => $local,
        // ], [
        //   'fecha_desde' => 'required|date',
        //   'fecha_hasta' => 'required|date|after_or_equal:fecha_hasta',
        //   'local' => 'required',
        // ]);

        // if ($validator->fails()) {
        //   return back()->withErrors($validator->errors());
        // } 
        
        // else {
        //   $fecha_desde_carbon = new Carbon($fecha_desde);
        //   $fecha_hasta_carbon = new Carbon($fecha_hasta);
        //   //  ---------
        //   $dias = config('app.reporte_mejores_clientes_dias_limite');
        //   if ($fecha_desde_carbon->addDays($dias)->isBefore($fecha_hasta_carbon)) {
        //     return back()->withErrors(['fecha_hasta' => "El lapso del reporte no puede superar los {$dias} dias"]);
        //   }
        // }
        });
      }
    }
}

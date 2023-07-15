<?php

namespace App\Http\Requests;

use App\SerieDocumento;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioAsignarDocumentoStoreRequest extends FormRequest
{
    public function authorize()
    {
      return true;
    }


    public function rules()
    {
      return [
        "usucodi"     => "required|exists:usuarios,usucodi",
        "empresa_id"     => "required|exists:opciones,empcodi",
        "loccodi"     => "required",
        "tidcodi"     => "required|exists:tipo_documento_pago,TidCodi",
        "sercodi"     => "required|max:4",
        "por_defecto" => "nullable",
        "estado"      => "nullable",
        'a4_plantilla_id' => 'required',
        'a5_plantilla_id' => 'nullable|sometimes',
        'ticket_plantilla_id' => 'nullable|sometimes',
        // 'a4_plantilla_id' => Rule::exists('pdf_plantillas')->where(function ($query) {
        //   $query->where('formato',  'a4');
        // }),
        // 'a5_plantilla_id' => Rule::exists('pdf_plantillas')->where(function ($query) {
        //   $query->where('formato',  'a5');
        // }),
        // 'ticket_plantilla_id' => Rule::exists('pdf_plantillas')->where(function ($query) {
        //   $query->where('formato',  'ticket');
        // }),
        
        'impresion_directa' => 'required|in:0,1',
        'cantidad_copias'   => 'nullable|integer',
        'nombre_impresora'  => 'nullable|sometimes|max:255',
      ];
    }


    public function withValidator($validator)
    {
      $validator->after(function($validator){

        $cant_documents = 
        SerieDocumento::where('usucodi',$this->usucodi)
        ->where('sercodi', $this->sercodi)
        ->where('loccodi', $this->loccodi)
        ->where('tidcodi', $this->tidcodi)
        ->where('empcodi', $this->empresa_id )
        ->count();

        if( $cant_documents ){
          $validator->errors()->add( 'sercodi' , 'La esta serie esta repetida ' );
        }
      });


    }
}


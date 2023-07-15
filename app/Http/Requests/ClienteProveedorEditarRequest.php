<?php

namespace App\Http\Requests;

use App\TipoDocumento;
use App\ClienteProveedor;
use Illuminate\Foundation\Http\FormRequest;

class ClienteProveedorEditarRequest extends FormRequest
{
  public $id_empresa;

  public function authorize()
  {
    return $this->user()->can('edit clientes');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $rules = [
      'codigo'         => 'required',
      // 'tipo_cliente'   => 'required|exists:prov_clientes_tipo,TippCodi',
      // 'tipo_cliente'         => 'required|in:',
      // 'tipo_cliente'   => 'required|exists:prov_clientes_tipo,TippCodi',
      // 'tipo_documento' => 'required|exists:prov_clientes_tipo_doc,TDocCodi',  
      'razon_social'   => 'required',            
      'telefono_1'     => 'nullable|numeric',
      'telefono_2'     => 'nullable|numeric',
      'email'          => 'nullable|email',            
      'vendedor'       => 'required:exists:vendedores,Vencodi',
      'moneda'         => 'required:exists:moneda,moncodi',            
      'lista_precio'   => 'required:exists:LisCodi,LisNomb',              
    ];


    if ( $this->tipo_documento == TipoDocumento::DNI ) {
      $rules['ruc'] = 'required|numeric|digits:8';
    } 
    
    elseif ( $this->tipo_documento == TipoDocumento::RUC ) {
      $rules['ruc'] = 'required|numeric|digits:11';
    } 
    
    elseif ( $this->tipo_documento == TipoDocumento::NINGUNA ) {
      $rules['ruc'] = 'nullable|sometimes|numeric';
    } 

    else {
      $rules['ruc'] = 'required|numeric';
    }

    return $rules;


    return $rules;
  }

  public function withValidator($validator)
  {

    if( $validator->fails() ){

      
      $validator->after(function($validator){

  // public static function find($id, $empcodi = null, $tipo = self::TIPO_CLIENTE)
  // {
  //   $parameters = [['PCCodi', $id], ['TipCodi', $tipo]];
  //   return self::where($parameters)->first();
  // }

        // cliente a editar
        $cliente_edit = ClienteProveedor::findByTipo($this->codigo, $this->tipo_cliente );

        if( ! $cliente_edit ){
          $validator->errors()->add('aa', 'Esta entidad no existe');
          return;
        }

        if( $cliente_edit->canEditDoc() ){
          $validator->errors()->add('aa', 'Esta entidad no existe');
        
        
        
        
        
        
        
        }


        if (!$cliente_edit) {
          $validator->errors()->add('aa', 'Esta entidad no existe');
          return;
        }


        // cliente a editar
        // $cliente_edit = ClienteProveedor::where( 
        //   ['PCCodi' => $this->codigo] , 
        //   ['TipCodi' => 'C']
        //   // ['EmpCodi' => $this->id_empresa ],
        // );
        
        //  -----------------------------------------------------------------  \\
        if( is_null($cliente_edit->first())  ){
          $validator->errors()->add('aa','Este cliente no existe');
        }
        
        if( ! $cliente_edit->count()  ){
          $validator->errors()->add('aa','Numero del documento esta repetido');
        }   
      
      });

    }
  }
}

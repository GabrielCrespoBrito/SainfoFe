<?php

namespace App\Http\Requests;

use App\Zona;
use App\Compra;
use App\Moneda;
use App\Producto;
use App\Vendedor;
use App\FormaPago;
use App\BaseImponible;
use App\SettingSystem;
use App\ClienteProveedor;
use Illuminate\Support\Facades\Log;
use App\Models\Venta\Traits\Calculator;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Venta\Traits\CalculatorTotal;

class CompraStoreRequest extends FormRequest
{
  public $totales_items = [];

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
			return true;
	}


  public function prepareForValidation()
  {
    $this->merge(['CpaSerie' => strtoupper($this->CpaSerie)]);
    $this->merge(['CpaNumee' => strtoupper($this->CpaNumee)]);
  }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
    $igvsPorcentajes = collect(SettingSystem::getIgvOpciones())
    ->pluck('porc')
    ->implode(',');

		$rules = [
      'TidCodi'  => 'required|in:01,03,07,40',
      'IGVEsta'  => 'required|in:0,1', 
			'CpaSerie' => 'required|alpha_num|max:4',
			'CpaNumee' => 'required|digits_between:1,12',
			'PCcodi'   => 'required',
			'CpaFCpa'  => 'required|date',
			'CpaFCon'  => 'required|date',
			'CpaFven'  => 'required|date',
			'moncodi'  => 'required|in:01,02',
			'CpaTCam'  => 'required|numeric|min:0.1',
			'concodi'  => 'required',
      'zona'  => 'required',
      'TpgCodi'  => 'required|exists:tipo_pago,TpgCodi',
			'Docrefe'  => 'max:20',
			'Cpaobse'  => 'max:100',
			'igv_porcentaje'  => 'required|in:' . $igvsPorcentajes,
			'items'    => 'required',
			'items.*.Detcodi' => 'required',
			'items.*.Detnomb' => 'required|max:100',
			'items.*.UniCodi' => 'required|numeric|min:0',
			'items.*.DetCant' => 'required|numeric|min:0',
			'items.*.DetDct1' => 'required|min:0|max:100',
			'items.*.DetDct2' => 'required|min:0|max:100',
			'items.*.DetPrec' => 'required|min:0.1',
			'items.*.DetImpo' => 'required|min:0',
			'items.*.update_costo' => 'required|in:0,1',
		];

		return $rules;
	}

  /**
   * Validar items
   * 
   * @return 
   */
  public function validateItems( &$validator, $items)
  {
    $calculator = new Calculator(true, $this->igv_porcentaje );
    $items_fixed = [];
    $index = 0;
    foreach ( $items as &$item )
    {

      $producto = Producto::findByProCodi($item['Detcodi']);

      if( is_null($producto) ){
        $validator->errors()->add('DetCodi', 'El codigo de producto es incorrecto');
        return;
      }

      else {
        $unidad = $producto->unidades->where('Unicodi', $item['UniCodi'] )->first();
        if( is_null($unidad) ){
          $validator->errors()->add('UniCodi', 'El codigo de la unidad es incorrecto');
          return;
        }
      }

      $incluye_igv =  $this->IGVEsta;
      $descuento =  $item['DetDct1'] + $item['DetDct2'];
      $factor = $unidad->getFactor();
      $tipo_cambio = $this->CpaTCam;;
      $is_sol = $this->moncodi == Moneda::SOL_ID;
      $calculator->setValues( $item['DetPrec'], $item['DetCant'], $incluye_igv, 'GRAVADA', $descuento, false, 0, $factor, $tipo_cambio, $is_sol );
      $calculator->calculate();
      $totales = $calculator->getCalculos();      
      
      $item['totales'] = $totales;
      $item['producto'] = $producto;
      $item['unidad'] = $unidad;
      $items_fixed[] = $item;
      $this->totales_items[] = $totales;
      $index++;
    }

    $this->merge(['items' => $items_fixed ]);
  }

  /**
   * Validar items
   * 
   * @return void
   */
  public function validateTotal( &$validator, $total )
  {
    if ( $this->total == 0) {
      $validator->errors()->add(
        'importe',
        'El importe total no puede ser igual a 0'
      );
      return;
    }


    $calculator = new CalculatorTotal( $this->totales_items );
    $calculator->calculateTotales();
    $total_ = $calculator->getTotal();
    if( $total_->total_cobrado !=  $this->total ) {
      $validator->errors()->add('importe', "El importe suministrador ({$this->total}) no coincide con el total correcto calculado ({$total_->total_cobrado})"
      );
    }
    $this->merge(['totales' => $total_ ]);
  }

	public function withValidator($validator)
	{
    if( !$validator->fails() ){
      
      $validator->after(function ($validator){
        
        if($this->route()->getName() == "compras.update" ){
          
          $compra = Compra::find( $this->route()->parameters()['id'] );

          if ( $compra->GuiOper ) {
            $validator->errors()->add('cliente_documento', 'No se puede modificar compra que ya tiene guia asociada');
            return;
          }
        }
        

				$cliente = ClienteProveedor::findByTipo( $this->PCcodi , 'P' );

				if( is_null($cliente) ){
	        $validator->errors()->add('cliente_documento','No existen este proveedor registrado en esta empresa' );	      	
				}

	      # Validar Forma de pago
	      $forma_pago = FormaPago::find($this->concodi);
	      if( is_null($forma_pago) ){
	        $validator->errors()->add('forma_pago','Esta forma de pago no existe');
	      }

        // Validar Zona
        $zona = Zona::find($this->zona);
        if (is_null($zona)) {
          $validator->errors()->add('zona', 'Este zona no existe');
        }

	      # Validar Vendedor
	      $vendedor = Vendedor::find($this->VenCodi);
	      if( is_null($vendedor) ){
	        $validator->errors()->add('vendedor','Este vendedor no existe');
				}

				
	      # Validar items
	      $items = collect($this->items);
	      $total =  $items->sum("DetImpo");

        // Validar items
        $this->validateItems($validator, $items );

        // Validar total
        $this->validateTotal($validator , $total);
			});
		}
	}

	public function messages(){
		return [
      'CpaTCam.min' => 'El tipo de cambio no puede ser Cero (0)',
    ];
	}
}
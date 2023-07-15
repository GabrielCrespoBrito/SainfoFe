<?php

namespace App\Http\Controllers;

use App\MotivoEgreso;
use App\MotivoIngreso;
use Illuminate\Http\Request;
use App\Http\Requests\DeleteMotivoRequest;
use App\Http\Requests\Motivo\MotivoStoreRequest;

class MotivosController extends Controller
{
  public $data_type = [];

  public function __construct()
  {
    $this->data_type = [
      'ingresos' => [
        'id' => 'IngCodi',
        'name' => 'IngNomb',
        'model' => new MotivoIngreso(),
        'last_id' => MotivoIngreso::UltimoId(),
      ],
      'egresos' => [
        'id' => 'EgrCodi',
        'name' => 'Egrnomb',
        'model' => new MotivoEgreso(),
        'last_id' => MotivoEgreso::UltimoId(),
      ]
    ];

  }


  public function index($type = 'ingresos')
  {
    $empresa = get_empresa();
    if($type == "ingresos"){
      $motivos = $empresa->motivosIngresos;
    }
    else {
      $motivos = $empresa->motivosEgresos;      
    }

    return view('motivos_movimientos.index', [
      'type' => $type,
      'motivos' => $motivos,
    ]);
  }

  public function search( Request $request, $type = 'I' )
  {
    $term = $request->data;
    $isIngreso = $type == 'I';
    $campoBusqueda = $isIngreso ? 'IngNomb' : 'Egrnomb';
    $model = $isIngreso ? new MotivoIngreso() : new MotivoEgreso();
    $motivos = $term ? 
    $model->where( $campoBusqueda, 'LIKE' , '%' . $term . '%'  )->get()->take(20) : 
    $model->get();

/*

<strong class="money_sol">{{ optional($caja->apertura())->ingreso_soles() }}
</strong>
</div>
<div class="input-group-addon">USD
<strong class="money_dolar"> {{ optional($caja->apertura())->ingreso_dolar() }}

*/

    $data = [];
    
    foreach( $motivos as $motivo ){
      $data[] = [
        'id' => $motivo->id,
        'text' => $motivo->descripcion,
      ];
    }
    return response()->json($data);
  }




  public function save( $type , MotivoStoreRequest $request )
  {
    $data_type = $this->data_type[$type];

    $this->validate( $request, ['name' => 'required|max:100']);
    $data = $request->all();
    $id = $type == "ingresos" ? MotivoIngreso::UltimoId() : MotivoEgreso::UltimoId();
    $data[ $data_type['id']   ] = $id;      
    $data[ $data_type['name']   ] = $request->name;          
    $data['EmpCodi'] = empcodi();      

    unset($data['id'],$data['name']);
    $model = $data_type['model'];
    $model->create($data);

  }

  public function edit( $type , Request $request )
  {
    $data_type = $this->data_type[$type];

    $this->validate( $request, [
      'name' => 'required'
    ]);

    $data[$data_type['name']]  = $request->name;

    if( $type == "ingresos" ){      
      MotivoIngreso::find($request->id)->update($data);
    }
    else {
      MotivoEgreso::find($request->id)->update($data);
    }
    return "Guardado registro satisfactoriamente";;
    
  }

  public function delete( DeleteMotivoRequest $request, $type )
  {
    $model =  $type == "ingresos" ? MotivoIngreso::find($request->id_motivo) : MotivoIngreso::find($request->id_motivo);

    $model->delete();

  }



}

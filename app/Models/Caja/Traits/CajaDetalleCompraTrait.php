<?php
namespace App\Models\Caja\Traits;

use App\CajaDetalle;
use App\Models\Compra\CompraPago;

trait CajaDetalleCompraTrait
{
  public function updateCompra()
  {
  }

  public static function storeCompra( CompraPago $pago, $request , $isIngreso = false )
  {
    $tipo_mov = $isIngreso ? 'I' : 'S';
    $campoSol = $isIngreso ? 'CANINGS' : 'CANEGRS';
    $campoDolar = $isIngreso ? 'CANINGD' : 'CANEGRD';

    $user = user_();
    $motivo = 'PAGO DE COMPRAS';
    $cuecodi = "0000";
    $sol = $pago->isSol() ? $pago->PagImpo : 0;
    $dolar = $pago->isSol() ? 0 : $pago->PagImpo;

    if( $pago->isBancario() ){
      $motivo = $pago->compra->cliente->PCNomb . '- (' . $pago->compra->correlative . ')';
      $cuecodi = $request->cuenta_id;
      $cuenta = $pago->cuenta();
      if( $pago->MonCodi != $cuenta->MonCodi ){
        
        if( $cuenta->isSol() ){
          $sol = $dolar * $pago->PagTCam;  
          $dolar = 0;
        }
        else {
          $dolar = $sol / $pago->PagTCam;
          $sol = 0;
        }
        
      }
    }

    else {
      if ($pago->docMismaCaja()) {
        $motivo = 'PAGO DE COMPRAS';
      } 
      
      else {
        $motivo = "PAGO - {$pago->compra->CpaFCpa}";
      }      
    }

    // cajnume;
    $c = new self;
    $data = [];
    $data["Id"] = $c->getLastIncrement('Id');
    $data["CueCodi"] = $cuecodi;
    $data["DocNume"] = $pago->PagOper;
    $data["CajNume"] = $pago->cajnume;
    $data["MocFech"] = $pago->PagFech;
    $data['MocFecV'] = null;
    $data["TIPMOV"]  = 'COMPRA';
    $data['empcodi'] = $pago->EmpCodi;
    $data["MocNomb"]  = $pago->compra->cliente->PCNomb;
    $data["CtoCodi"] = "002";
    $data["MonCodi"] = $pago->MonCodi;
    // $data["CANINGS"] = 0;
    // $data["CANINGD"] = 0;
    // $data["CANEGRS"] = $sol;
    // $data["CANEGRD"] = $dolar;
    $data[$campoSol] = $sol;
    $data[$campoDolar] = $dolar;
    $data["SALSOLE"] = 0;
    $data["SALDOLA"] = 0;
    $data["TIPCAMB"] = $pago->PagTCam;
    // $data["ANULADO"] = 'S'  ;
    $data["ANULADO"] = $tipo_mov;
    $data["MOTIVO"] = $motivo;
    $data["AUTORIZA"] = $user->usulogi;
    $data["OTRODOC"] = $data["DocNume"];
    $data["LocCodi"] = "001";
    $data["UsuCodi"] = $user->usucodi;
    $data["EgrIng"] = null;
    $data["PCCodi"] = null;
    $data["TDocCodi"] = NULL;
    $data["User_Crea"] = $user->usulogi;
    $data["User_ECrea"] = gethostname();
    $egreso = self::create($data);
    $egreso->setMocCorrelative();
    $pago->caja->calculateSaldo();
  }

  public static function eliminarCompraPago( $compra_pago, $caja )
  {
    $caja_mov = $compra_pago->caja_movimiento;
    $data =  $caja_mov->toArray();
    $user = auth()->user();
    $compra = $compra_pago->compra;
    $motivo = sprintf("ANULADO %s-%s-%s %s", $compra->TidCodi, $compra->CpaSerie, $compra->CpaNumee, $compra->CpaFCpa);
    $c = new self;
    $data["Id"] = $c->getLastIncrement('Id');
    $data["CajNume"] = $caja->CajNume;
    $data["MocFech"] = datePeru('Y-m-d');
    $data["CANINGS"] = $data["CANEGRS"];
    $data["CANINGD"] = $data["CANEGRD"];
    $data["CANEGRS"] = 0;
    $data["CANEGRD"] = 0;
    $data["ANULADO"] = 'I';
    $data["MOTIVO"] = $motivo;
    $data["AUTORIZA"] = $user->usulogi;
    $data["UsuCodi"] = $user->usucodi;
    $data["User_Crea"] = $user->usulogi;
    $data["User_ECrea"] = gethostname();
    $caja_detalle = CajaDetalle::create($data);
    $caja_detalle->setMocCorrelative();     
  }


  public static function eliminarVentaPago( $venta_pago, $caja )
  {
    $caja_mov = $venta_pago->caja_movimiento;
    $data =  $caja_mov->toArray();
    $user = user_();    
    $venta = $venta_pago->venta;
    $motivo = sprintf("ANULADO %s-%s-%s %s", $venta->TidCodi , $venta->VtaSeri , $venta->VtaNumee , $venta->VtaFvta );  
    
    $c = new self;
    $data["Id"] = $c->getLastIncrement('Id');
    $data["CajNume"] = $caja->CajNume;
    $data["MocFech"] = datePeru('Y-m-d');
    $data["CANEGRS"] = $data["CANINGS"];
    $data["CANEGRD"] = $data["CANINGD"];
    $data["CANINGS"] = 0;
    $data["CANINGD"] = 0;    
    $data["ANULADO"] = 'S';
    $data["MOTIVO"] = $motivo;
    $data["AUTORIZA"] = $user->usulogi;
    $data["UsuCodi"] = $user->usucodi;
    $data["User_Crea"] = $user->usulogi;
    $data["User_ECrea"] = gethostname();

    $caja_detalle = CajaDetalle::create($data);
    $caja_detalle->setMocCorrelative();     
  }
  

}
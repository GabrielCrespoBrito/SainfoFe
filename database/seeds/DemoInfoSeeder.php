<?php

use Illuminate\Database\Seeder;
use App\Empresa;
use App\UserEmpresa;
use App\Periodo;
use App\Almacen;
use App\Caja;
use App\EmpresaOpcion;
use App\SerieDocumento;

class DemoInfoSeeder extends Seeder
{
	public function run()
	{
		function deleteIfExist($model)
		{
			if (!is_null($model)) $model->delete();
		}

		$empcodi = "002";
		$usucodi = "22";
		$locodi = "111";
		
		// Usuario
			if( $user = User::findByUserName("DEMO")){
				$user->delete();
			}
			
			$user = new User;
			$user->usucodi = $usucodi;
			$user->usulogi = "DEMO";      
			$user->usunomb = "DEMO";
			$user->usucla1 = "DEMO";
			$user->usucla2 = "DEMO";
			$user->carcodi = "11";
			$user->ususerf = "F001";
			$user->usudocf = "000000";
			$user->ususerb = "001";
			$user->email = "demo@gmail.com";
			$user->save();



		# Crear Empresa
		deleteIfExist(Empresa::find(2));

		$empresa = Empresa::first();
		$data = $empresa->toArray();
		$data["id"] = '2';      
		$data["empcodi"] = $empcodi;
		$data["EmpLin1"] = "10323013760";    
		$data["EmpNomb"] = 'EMPRESA DEMO';
		Empresa::create($data);

		# Asociar el usuario con la empresa
		deleteIfExist(UserEmpresa::where('usucodi',$usucodi)->where('empcodi',$empcodi)->first());

		$usuario_empresa = new UserEmpresa;
		$usuario_empresa->usucodi = $usucodi;
		$usuario_empresa->empcodi = $empcodi;
		$usuario_empresa->estado = 1;
		$usuario_empresa->save();

		# Crear Local de la Empresa
		deleteIfExist(Almacen::find($locodi));

		$almacen = new Almacen;
		$almacen->LocCodi = $locodi;
		$almacen->LocNomb = "LOCAL DEMO";
		$almacen->EmpCodi = $empcodi;
		$almacen->save();

		# Crear el periodo
		deleteIfExist(Periodo::where('Pan_cAnio',"2018")->where('empcodi',$empcodi)->first());

		$periodo = new Periodo;
		$periodo->empcodi = $empcodi;
		$periodo->Pan_cAnio = "2018";
		$periodo->Pan_cEstado = "A";
		$periodo->save();

		# Caja
		deleteIfExist( Caja::find("122-000003") );
		
		$caja = new Caja;
		$caja->CajNume = "122-000003";
		$caja->CajEsta = "Ap";
		$caja->empcodi = $empcodi;
		$caja->CueCodi = "0000";
		$caja->UsuCodi = $usucodi;
		$caja->PanAno = "2018";
		$caja->save();

		# Caja
		deleteIfExist( EmpresaOpcion::find("9999999") );

		$empresa_opcion = EmpresaOpcion::first();
		$data = $empresa_opcion->toArray();
		$data['UltCpra'] = "9999999";
		$data['OpcConta'] = 0;
		$data['DesAuto'] = 0;
		$data['ImpSald'] = 0;
		$data['EmpCodi'] = $empcodi;
		EmpresaOpcion::create($data);;

		# User Documento
		deleteIfExist( SerieDocumento::find("444") );
		deleteIfExist( SerieDocumento::find("555") );
		deleteIfExist( SerieDocumento::find("666") );
		deleteIfExist( SerieDocumento::find("777") );

		$serie_1 = new SerieDocumento;
		$serie_1->ID = 444;
		$serie_1->empcodi = $empcodi;
		$serie_1->usucodi = $usucodi;
		$serie_1->tidcodi = "01";
		$serie_1->sercodi = "F001";
		$serie_1->numcodi = "000000";
		$serie_1->defecto = 1;
		$serie_1->loccodi = $locodi;
		$serie_1->estado = 1;
		$serie_1->save();

		$serie_1 = new SerieDocumento;
		$serie_1->ID = 555;
		$serie_1->empcodi = $empcodi;
		$serie_1->usucodi = $usucodi;
		$serie_1->tidcodi = "03";
		$serie_1->sercodi = "B001";
		$serie_1->numcodi = "000000";
		$serie_1->defecto = 1;
		$serie_1->loccodi = $locodi;
		$serie_1->estado = 1;
		$serie_1->save();

		$serie_1 = new SerieDocumento;
		$serie_1->ID = 666;
		$serie_1->empcodi = $empcodi;
		$serie_1->usucodi = $usucodi;
		$serie_1->tidcodi = "07";
		$serie_1->sercodi = "F001";
		$serie_1->numcodi = "000000";
		$serie_1->defecto = 1;
		$serie_1->loccodi = $locodi;
		$serie_1->estado = 1;
		$serie_1->save();

		$serie_1 = new SerieDocumento;
		$serie_1->ID = 777;
		$serie_1->empcodi = $empcodi;
		$serie_1->usucodi = $usucodi;
		$serie_1->tidcodi = "08";
		$serie_1->sercodi = "F001";
		$serie_1->numcodi = "000000";
		$serie_1->defecto = 1;
		$serie_1->loccodi = $locodi;
		$serie_1->estado = 1;
		$serie_1->save();    



	}



}

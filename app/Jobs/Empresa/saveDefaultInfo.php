<?php

namespace App\Jobs\Empresa;

use App\Grupo;
use App\Marca;
use App\Moneda;
use App\Familia;
use App\Producto;
use App\Vendedor;
use App\FormaPago;
use App\ListaPrecio;
use App\BancoEmpresa;
use App\ClienteProveedor;
use App\CondicionVenta;
use App\Empresa;
use App\Local;
use Illuminate\Foundation\Bus\Dispatchable;

class saveDefaultInfo
{
  use Dispatchable;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  protected $empresa;
  protected $createLocal;

  public function __construct(Empresa $empresa, $createLocal = false)
  {
    $this->empresa = $empresa;
    $this->createLocal = $createLocal;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $empcodi = $this->empresa->empcodi;
    # Condicion de venta
    CondicionVenta::createDefault($empcodi);
    // # Condicion
    FormaPago::createDefault($empcodi);
    // # Vendedor
    Vendedor::createDefault($empcodi);
    # Grupo
    Grupo::createDefault($empcodi);
    # Familia
    Familia::createDefault($empcodi);
    # Marca
    Marca::createDefault($empcodi);
    # Moneda
    Moneda::createDefault($empcodi);
    # Lista de precio
    $lista_precio = ListaPrecio::createDefault($empcodi);
    # Producto
    Producto::createDefault($empcodi, $lista_precio, null, 'P');
    # Banco de la empresa
    BancoEmpresa::createDefault($empcodi);
    # Crear cliente y proveedor por defecto para las boletas 
    ClienteProveedor::createDefault($empcodi, ClienteProveedor::TIPO_CLIENTE, 'QUITAR DEL ALMACEN', ClienteProveedor::DEFAULT_CODIGO_ALMACEN );
    ClienteProveedor::createDefault($empcodi, ClienteProveedor::TIPO_PROVEEDOR, 'AGREGAR DEL ALMACEN', ClienteProveedor::DEFAULT_CODIGO_ALMACEN );
    ClienteProveedor::createDefault($empcodi, ClienteProveedor::TIPO_CLIENTE, 'CLIENTES VARIOS', ClienteProveedor::DEFAULT_CODIGO );
    ClienteProveedor::createDefault($empcodi, ClienteProveedor::TIPO_PROVEEDOR, 'PROVEEDORES VARIOS', ClienteProveedor::DEFAULT_CODIGO );

    $empresa = Empresa::find($empcodi);

    if ($this->createLocal) {
      Local::createLocalDefault($empcodi, $empresa->EmpLin2, $empresa->EmpLin4, $empresa->FE_UBIGEO );
    }
  }
}

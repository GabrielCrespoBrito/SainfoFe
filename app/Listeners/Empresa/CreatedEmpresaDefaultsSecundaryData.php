<?php

namespace App\Listeners\Empresa;

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
use App\MotivoEgreso;
use App\MotivoIngreso;

class CreatedEmpresaDefaultsSecundaryData
{
    public function __construct()
    {
        //
    }

    public function handle($event)
    {
        return;
        $empcodi = $event->empresa->empcodi;
        # Condicion de venta
        CondicionVenta::createDefault($empcodi);
        # Condicion
        FormaPago::createDefault($empcodi);
        # Vendedor        
        Vendedor::createDefault($empcodi);
        # Grupo
        Grupo::createDefault($empcodi);
        # Familia
        Familia::createDefault($empcodi);
        # Marca
        Marca::createDefault($empcodi);
        # Marca
        Moneda::createDefault($empcodi);
        # Lista de precio
        $lista_precio = ListaPrecio::createDefault($empcodi);
        # Producto
        Producto::createDefault($empcodi, $lista_precio);
        # Banco de la empresa
        BancoEmpresa::createDefault($empcodi);
        # Cliente por defecto
        ClienteProveedor::createDefaults($empcodi);
        # Tipos de Ingresos y Egresos
        MotivoEgreso::createDefault($empcodi);
        MotivoIngreso::createDefault($empcodi);
    }
}
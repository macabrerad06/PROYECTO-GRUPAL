<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\repositories\CategoriaRepository;
use App\repositories\ClienteRepository;
use App\repositories\FacturaRepository;
use App\repositories\ProductoRepository;

use App\entities\Categoria;
use App\entities\Cliente;
use App\entities\Factura;
use App\entities\Producto;

//prueba de categoria
$categoriaRepository = new CategoriaRepository();

$categorias = $categoriaRepository->findAll();

foreach ($categorias as $categoria) {
    echo "ID: " . $categoria->getId() . " - Nombre: " . $categoria->getNombre() . " - Descripción: " . $categoria->getDescripcion();
}

// prueba de cliente
$clienteRepository = new ClienteRepository();

$clientes = $clienteRepository->findAll();

foreach ($clientes as $cliente) {
    echo "ID: " . $cliente->getId() . " - Nombre: " . $cliente->getNombre() . " - Correo: " . $cliente->getCorreo();
}

//prueba de factura
$facturaRepository = new FacturaRepository();

$facturas = $facturaRepository->findAll();

foreach ($facturas as $factura) {
    echo "ID: " . $factura->getId() . " - Fecha: " . $factura->getFechaEmision() . " - Estado: " . $factura->getEstado();
}

//prueba de producto
$productoRepository = new ProductoRepository();

$productos = $productoRepository->findAll();

foreach ($productos as $producto) {
    echo "ID: " . $producto->getId() . " - Nombre: " . $producto->getNombre() . " - Precio: " . $producto->getPrecioUnitario();
}
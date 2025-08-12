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
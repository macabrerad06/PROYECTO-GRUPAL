<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Producto;
use App\Repositories\ProductoRepository;
use Exception;

class ProductoController
{
    private ProductoRepository $productoRepository;

    public function __construct()
    {
        $this->productoRepository = new ProductoRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $producto = $this->productoRepository->findById((int)$_GET['id']);
                echo json_encode($producto ? $this->productoToArray($producto) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'productoToArray'],
                    $this->productoRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $producto = new Producto(
                    null,
                    $payload['nombre'],
                    $payload['descripcion'],
                    $payload['precioUnitario'],
                    $payload['stock'],
                    $payload['idCategoria'],
                    $payload['tipoProducto'],
                );
                echo json_encode(['success' => $this->productoRepository->create($producto)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->productoRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Producto not found']);
                return;
            }

            if (isset($payload['nombre'])) $existing->setNombre($payload['nombre']);
            if (isset($payload['descripcion'])) $existing->setDescripcion($payload['descripcion']);
            if (isset($payload['precioUnitario'])) $existing->setPrecioUnitario($payload['precioUnitario']);
            if (isset($payload['idCategoria'])) $existing->setIdCategoria($payload['idCategoria']);
            if (isset($payload['tipoProducto'])) $existing->setTipoProducto($payload['tipoProducto']);
            if (isset($payload['stock'])) $existing->setStock($payload['stock']);

            echo json_encode(['success' => $this->productoRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->productoRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function productoToArray(Producto $producto): array
    {
        return [
            'id' => $producto->getId(),
            'nombre' => $producto->getNombre(),
            'descripcion' => $producto->getDescripcion(),
            'precioUnitario' => $producto->getPrecioUnitario(),
            'stock' => $producto->getStock(),
            'idCategoria' => $producto->getIdCategoria(),
            'tipoProducto' => $producto->getTipoProducto(),
        ];
    }
}   
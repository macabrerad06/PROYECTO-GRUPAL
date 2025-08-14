<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\DetalleVenta;
use App\Repositories\DetalleVentaRepository;
use Exception;

class DetalleVentaController
{
    private DetalleVentaRepository $detalleVentaRepository;

    public function __construct()
    {
        $this->detalleVentaRepository = new DetalleVentaRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['idVenta'])) {
                $detalleVenta = $this->detalleVentaRepository->findById((int)$_GET['idVenta']);
                echo json_encode($detalleVenta ? $this->detalleVentaToArray($detalleVenta) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'detalleVentaToArray'],
                    $this->detalleVentaRepository->findAll()
                );
                echo json_encode(['data' => $list]); // Se envuelve en una clave 'data' para el proxy
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $detalleVenta = new DetalleVenta(
                    $payload['id_venta'], // Se cambia a id_venta para que coincida con el modelo
                    $payload['line_number'], // Se cambia a line_number
                    $payload['id_producto'],
                    $payload['cantidad'],
                    $payload['precio_unitario'],
                    $payload['subtotal']
                );
                echo json_encode(['success' => $this->detalleVentaRepository->create($detalleVenta)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }

        if ($method === 'PUT') {
            $idVenta = (int)($payload['id_venta'] ?? 0);
            $lineNumber = (int)($payload['line_number'] ?? 0);
            $existing = $this->detalleVentaRepository->findById($idVenta, $lineNumber);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'DetalleVenta not found']);
                return;
            }

            if (isset($payload['id_producto'])) $existing->setIdProducto($payload['id_producto']);
            if (isset($payload['cantidad'])) $existing->setCantidad($payload['cantidad']);
            if (isset($payload['precio_unitario'])) $existing->setPrecioUnitario($payload['precio_unitario']);
            if (isset($payload['subtotal'])) $existing->setSubtotal($payload['subtotal']);

            echo json_encode(['success' => $this->detalleVentaRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $idVenta = (int)($payload['id_venta'] ?? 0);
            $lineNumber = (int)($payload['line_number'] ?? 0);
            if ($idVenta === 0 || $lineNumber === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID Venta or Line Number not provided']);
                return;
            }
            echo json_encode(['success' => $this->detalleVentaRepository->delete($idVenta, $lineNumber)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function detalleVentaToArray(DetalleVenta $detalleVenta): array
    {
        return [
            'id_venta' => $detalleVenta->getIdVenta(),
            'line_number' => $detalleVenta->getLineNumber(),
            'id_producto' => $detalleVenta->getIdProducto(),
            'cantidad' => $detalleVenta->getCantidad(),
            'precio_unitario' => $detalleVenta->getPrecioUnitario(),
            'subtotal' => $detalleVenta->getSubtotal()
        ];
    }
}
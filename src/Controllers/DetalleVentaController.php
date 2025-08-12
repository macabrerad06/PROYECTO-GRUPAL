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
            if (isset($_GET['id'])) {
                $detalleVenta = $this->detalleVentaRepository->findById((int)$_GET['id']);
                echo json_encode($detalleVenta ? $this->detalleVentaToArray($detalleVenta) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'detalleVentaToArray'],
                    $this->detalleVentaRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $detalleVenta = new DetalleVenta(
                    null,
                    $payload['lineNumber'],
                    $payload['idProducto'],
                    $payload['cantidad'],
                    $payload['precioUnitario'],
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
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->detalleVentaRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'DetalleVenta not found']);
                return;
            }

            if (isset($payload['lineNumber'])) $existing->setLineNumber($payload['lineNumber']);
            if (isset($payload['idProducto'])) $existing->setIdProducto($payload['idProducto']);
            if (isset($payload['cantidad'])) $existing->setCantidad($payload['cantidad']);
            if (isset($payload['precioUnitario'])) $existing->setPrecioUnitario($payload['precioUnitario']);
            if (isset($payload['subtotal'])) $existing->setSubtotal($payload['subtotal']);

            echo json_encode(['success' => $this->detalleVentaRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['idVenta'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->detalleVentaRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function detalleVentaToArray(DetalleVenta $detalleVenta): array
    {
        return [
            'idVenta' => $detalleVenta->getIdVenta(),
            'lineNumber' => $detalleVenta->getLineNumber(),
            'idProducto' => $detalleVenta->getIdProducto(),
            'cantidad' => $detalleVenta->getCantidad(),
            'precioUnitario' => $detalleVenta->getPrecioUnitario(),
            'subtotal' => $detalleVenta->getSubtotal()
        ];
    }
}
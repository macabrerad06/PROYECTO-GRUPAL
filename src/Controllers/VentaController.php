<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Venta;
use App\Repositories\VentaRepository;
use Exception;

class VentaController
{
    private VentaRepository $ventaRepository;

    public function __construct()
    {
        $this->ventaRepository = new VentaRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $venta = $this->ventaRepository->findById((int)$_GET['id']);
                echo json_encode($venta ? $this->ventaToArray($venta) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'ventaToArray'],
                    $this->ventaRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $venta = new Venta(
                    null,
                    $payload['fecha'],
                    $payload['idCliente'],
                    $payload['total'],
                    $payload['estado']
                );
                echo json_encode(['success' => $this->ventaRepository->create($venta)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->ventaRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Venta not found']);
                return;
            }

            if (isset($payload['fecha'])) $existing->setFecha($payload['fecha']);
            if (isset($payload['idCliente'])) $existing->setIdCliente($payload['idCliente']);
            if (isset($payload['total'])) $existing->setTotal($payload['total']);
            if (isset($payload['estado'])) $existing->setEstado($payload['estado']);

            echo json_encode(['success' => $this->ventaRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->ventaRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function ventaToArray(Venta $venta): array
    {
        return [
            'id' => $venta->getId(),
            'fecha' => $venta->getFecha(),
            'idCliente' => $venta->getIdCliente(),
            'total' => $venta->getTotal(),
            'estado' => $venta->getEstado(),
        ];
    }
}   
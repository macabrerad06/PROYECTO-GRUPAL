<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Factura;
use App\Repositories\FacturaRepository;
use Exception;

class FacturaController
{
    private FacturaRepository $facturaRepository;

    public function __construct()
    {
        $this->facturaRepository = new FacturaRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $factura = $this->facturaRepository->findById((int)$_GET['id']);
                echo json_encode($factura ? $this->facturaToArray($factura) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'facturaToArray'],
                    $this->facturaRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $factura = new Factura(
                    null,
                    $payload['idVenta'],
                    $payload['numero'],
                    $payload['claveAcceso'],
                    $payload['fechaEmision'],
                    $payload['estado']
                );
                echo json_encode(['success' => $this->facturaRepository->create($factura)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->facturaRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Factura not found']);
                return;
            }

            if (isset($payload['idVenta'])) $existing->setIdVenta($payload['idVenta']);
            if (isset($payload['numero'])) $existing->setNumero($payload['numero']);
            if (isset($payload['claveAcceso'])) $existing->setClaveAcceso($payload['claveAcceso']);
            if (isset($payload['fechaEmision'])) $existing->setFechaEmision($payload['fechaEmision']);
            if (isset($payload['estado'])) $existing->setEstado($payload['estado']);

            echo json_encode(['success' => $this->facturaRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->facturaRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function facturaToArray(Factura $factura): array
    {
        return [
            'id' => $factura->getId(),
            'idVenta' => $factura->getIdVenta(),
            'numero' => $factura->getNumero(),
            'claveAcceso' => $factura->getClaveAcceso(),
            'fechaEmision' => $factura->getFechaEmision(),
            'estado' => $factura->getEstado()
        ];
    }
}
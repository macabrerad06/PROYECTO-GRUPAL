<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\ProductoFisico;
use App\Repositories\ProductoFisicoRepository;
use Exception;

class ProductoFisicoController
{
    private ProductoFisicoRepository $productoFisicoRepository;

    public function __construct()
    {
        $this->productoFisicoRepository = new ProductoFisicoRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $productoFisico = $this->productoFisicoRepository->findById((int)$_GET['id']);
                echo json_encode($productoFisico ? $this->productoFisicoToArray($productoFisico) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'productoFisicoToArray'],
                    $this->productoFisicoRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $productoFisico = new ProductoFisico(
                    null,
                    $payload['peso'],
                    $payload['alto'],
                    $payload['ancho'],
                    $payload['profundidad']
                );
                echo json_encode(['success' => $this->productoFisicoRepository->create($productoFisico)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->productoFisicoRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Producto Físico not found']);
                return;
            }

            if (isset($payload['peso'])) $existing->setPeso($payload['peso']);
            if (isset($payload['alto'])) $existing->setAlto($payload['alto']);
            if (isset($payload['ancho'])) $existing->setAncho($payload['ancho']);
            if (isset($payload['profundidad'])) $existing->setProfundidad($payload['profundidad']);

            echo json_encode(['success' => $this->productoFisicoRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->productoFisicoRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function productoFisicoToArray(ProductoFisico $productoFisico): array
    {
        return [
            'id' => $productoFisico->getId(),
            'peso' => $productoFisico->getPeso(),
            'alto' => $productoFisico->getAlto(),
            'ancho' => $productoFisico->getAncho(),
            'profundidad' => $productoFisico->getProfundidad(),
        ];
    }
}   
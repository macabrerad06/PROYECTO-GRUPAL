<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\ProductoDigital;
use App\Repositories\ProductoDigitalRepository;
use Exception;

class ProductoDigitalController
{
    private ProductoDigitalRepository $productoDigitalRepository;

    public function __construct()
    {
        $this->productoDigitalRepository = new ProductoDigitalRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $productoDigital = $this->productoDigitalRepository->findById((int)$_GET['id']);
                echo json_encode($productoDigital ? $this->productoDigitalToArray($productoDigital) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'productoDigitalToArray'],
                    $this->productoDigitalRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $productoDigital = new ProductoDigital(
                    null,
                    $payload['urlDescarga'],
                    $payload['Licencia']
                );
                echo json_encode(['success' => $this->productoDigitalRepository->create($productoDigital)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->productoDigitalRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Producto Digital not found']);
                return;
            }

            if (isset($payload['urlDescarga'])) $existing->setUrlDescarga($payload['urlDescarga']);
            if (isset($payload['Licencia'])) $existing->setLicencia($payload['Licencia']);

            echo json_encode(['success' => $this->productoDigitalRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->productoDigitalRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function productoDigitalToArray(ProductoDigital $productoDigital): array
    {
        return [
            'id' => $productoDigital->getId(),
            'urlDescarga' => $productoDigital->getUrlDescarga(),
            'Licencia' => $productoDigital->getLicencia(),
        ];
    }
}   
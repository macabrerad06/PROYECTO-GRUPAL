<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Permiso;
use App\Repositories\PermisoRepository;
use Exception;

class PermisoController
{
    private PermisoRepository $permisoRepository;

    public function __construct()
    {
        $this->permisoRepository = new PermisoRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $permiso = $this->permisoRepository->findById((int)$_GET['id']);
                echo json_encode($permiso ? $this->permisoToArray($permiso) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'permisoToArray'],
                    $this->permisoRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $permiso = new Permiso(
                    null,
                    $payload['codigo']
                );
                echo json_encode(['success' => $this->permisoRepository->create($permiso)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->permisoRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Permiso not found']);
                return;
            }

            if (isset($payload['codigo'])) $existing->setCodigo($payload['codigo']);

            echo json_encode(['success' => $this->permisoRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->permisoRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function permisoToArray(Permiso $permiso): array
    {
        return [
            'id' => $permiso->getId(),
            'codigo' => $permiso->getCodigo()
        ];
    }
}   
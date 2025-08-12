<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Rol;
use App\Repositories\RolRepository;
use Exception;

class RolController
{
    private RolRepository $rolRepository;

    public function __construct()
    {
        $this->rolRepository = new RolRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $rol = $this->rolRepository->findById((int)$_GET['id']);
                echo json_encode($rol ? $this->rolToArray($rol) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'rolToArray'],
                    $this->rolRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $rol = new Rol(
                    null,
                    $payload['nombre'],
                );
                echo json_encode(['success' => $this->rolRepository->create($rol)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->rolRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Rol not found']);
                return;
            }

            if (isset($payload['nombre'])) $existing->setNombre($payload['nombre']);

            echo json_encode(['success' => $this->rolRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->rolRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function rolToArray(Rol $rol): array
    {
        return [
            'id' => $rol->getId(),
            'nombre' => $rol->getNombre(),
        ];
    }
}   
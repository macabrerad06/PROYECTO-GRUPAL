<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\RolPermiso;
use App\Repositories\RolPermisoRepository;
use Exception;

class RolPermisoController
{
    private RolPermisoRepository $rolPermisoRepository;

    public function __construct()
    {
        $this->rolPermisoRepository = new RolPermisoRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['idRol'])) {
                $rolPermiso = $this->rolPermisoRepository->findById((int)$_GET['idRol']);
                echo json_encode($rolPermiso ? $this->rolPermisoToArray($rolPermiso) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'rolPermisoToArray'],
                    $this->rolPermisoRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $rolPermiso = new RolPermiso(
                    $payload['idRol'],
                    $payload['idPermiso'],
                );
                echo json_encode(['success' => $this->rolPermisoRepository->create($rolPermiso)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['idRol'] ?? 0);
            $existing = $this->rolPermisoRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'RolPermiso not found']);
                return;
            }

            if (isset($payload['idRol'])) $existing->setIdRol($payload['idRol']);
            if (isset($payload['idPermiso'])) $existing->setIdPermiso($payload['idPermiso']);

            echo json_encode(['success' => $this->rolPermisoRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['idRol'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->rolPermisoRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function rolPermisoToArray(RolPermiso $rolPermiso): array
    {
        return [
            'idRol' => $rolPermiso->getIdRol(),
            'idPermiso' => $rolPermiso->getIdPermiso()            
        ];
    }
}   
<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Usuario;
use App\Repositories\UsuarioRepository;
use Exception;

class UsuarioController
{
    private UsuarioRepository $usuarioRepository;

    public function __construct()
    {
        $this->usuarioRepository = new UsuarioRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $usuario = $this->usuarioRepository->findById((int)$_GET['id']);
                echo json_encode($usuario ? $this->usuarioToArray($usuario) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'usuarioToArray'],
                    $this->usuarioRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $usuario = new Usuario(
                    null,
                    $payload['username'],
                    $payload['passwordHash'],
                    $payload['estado']
                );
                echo json_encode(['success' => $this->usuarioRepository->create($usuario)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->usuarioRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Usuario not found']);
                return;
            }

            if (isset($payload['username'])) $existing->setUsername($payload['username']);
            if (isset($payload['passwordHash'])) $existing->setPasswordHash($payload['passwordHash']);
            if (isset($payload['estado'])) $existing->setEstado($payload['estado']);

            echo json_encode(['success' => $this->usuarioRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->usuarioRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function usuarioToArray(Usuario $usuario): array
    {
        return [
            'id' => $usuario->getId(),
            'username' => $usuario->getUsername(),
            'passwordHash' => $usuario->getPasswordHash(),
            'estado' => $usuario->getEstado(),
        ];
    }
}   
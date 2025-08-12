<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Categoria;
use App\Repositories\CategoriaRepository;
use Exception;

class CategoriaController
{
    private CategoriaRepository $categoriaRepository;

    public function __construct()
    {
        $this->categoriaRepository = new CategoriaRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $categoria = $this->categoriaRepository->findById((int)$_GET['id']);
                echo json_encode($categoria ? $this->categoriaToArray($categoria) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'categoriaToArray'],
                    $this->categoriaRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $categoria = new Categoria(
                    null,
                    $payload['nombre'],
                    $payload['descripcion'],
                    $payload['estado'],
                    $payload['idPadre']
                );
                echo json_encode(['success' => $this->categoriaRepository->create($categoria)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }

        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->categoriaRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Category not found']);
                return;
            }

            if (isset($payload['nombre'])) $existing->setNombre($payload['nombre']);
            if (isset($payload['descripcion'])) $existing->setDescripcion($payload['descripcion']);
            if (isset($payload['estado'])) $existing->setEstado($payload['estado']);
            if (isset($payload['idPadre'])) $existing->setIdPadre($payload['idPadre']);

            echo json_encode(['success' => $this->categoriaRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->categoriaRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function categoriaToArray(Categoria $categoria): array
    {
        return [
            'id' => $author->getId(),
            'nombre' => $categoria->getNombre(),
            'descripcion' => $categoria->getDescripcion(),
            'estado' => $categoria->getEstado(),
            'idPadre' => $categoria->getIdPadre()
        ];
    }
}
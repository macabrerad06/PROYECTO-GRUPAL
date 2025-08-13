<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\PersonaNatural;
use App\Repositories\PersonaNaturalRepository;
use Exception;

class PersonaNaturalController
{
    private PersonaNaturalRepository $personaNaturalRepository;

    public function __construct()
    {
        $this->personaNaturalRepository = new PersonaNaturalRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $personaNatural = $this->personaNaturalRepository->findById((int)$_GET['id']);
                echo json_encode($personaNatural ? $this->personaNaturalToArray($personaNatural) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'personaNaturalToArray'],
                    $this->personaNaturalRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $personaNatural = new PersonaNatural(
                    null,
                    $payload['nombre'],
                    $payload['apellido'],
                    $payload['cedula']
                );
                echo json_encode(['success' => $this->personaNaturalRepository->create($personaNatural)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->personaNaturalRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Persona Natural not found']);
                return;
            }

            if (isset($payload['nombre'])) $existing->setNombre($payload['nombre']);
            if (isset($payload['apellido'])) $existing->setApellido($payload['apellido']);
            if (isset($payload['cedula'])) $existing->setCedula($payload['cedula']);
    
            echo json_encode(['success' => $this->personaNaturalRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->personaNaturalRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function personaNaturalToArray(PersonaNatural $personaNatural): array
    {
        return [
            'id' => $personaNatural->getId(),
            'nombre' => $personaNatural->getNombre(),
            'apellido' => $personaNatural->getApellido(),
            'cedula' => $personaNatural->getCedula(),
        ];
    }
}   
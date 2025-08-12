<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\PersonaJuridica;
use App\Repositories\PersonaJuridicaRepository;
use Exception;

class PersonaJuridicaController
{
    private PersonaJuridicaRepository $personaJuridicaRepository;

    public function __construct()
    {
        $this->personaJuridicaRepository = new PersonaJuridicaRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $personaJuridica = $this->personaJuridicaRepository->findById((int)$_GET['id']);
                echo json_encode($personaJuridica ? $this->personaJuridicaToArray($personaJuridica) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'personaJuridicaToArray'],
                    $this->personaJuridicaRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $personaJuridica = new PersonaJuridica(
                    null,
                    $payload['razonSocial'],
                    $payload['ruc'],
                    $payload['representanteLegal']
                );
                echo json_encode(['success' => $this->personaJuridicaRepository->create($personaJuridica)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }
             
        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->personaJuridicaRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Persona Juridica not found']);
                return;
            }

            if (isset($payload['razonSocial'])) $existing->setRazonSocial($payload['razonSocial']);
            if (isset($payload['ruc'])) $existing->setRuc($payload['ruc']);
            if (isset($payload['representanteLegal'])) $existing->setRepresentanteLegal($payload['representanteLegal']);

            echo json_encode(['success' => $this->personaJuridicaRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->personaJuridicaRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function personaJuridicaToArray(PersonaJuridica $personaJuridica): array
    {
        return [
            'id' => $personaJuridica->getId(),
            'razonSocial' => $personaJuridica->getRazonSocial(),
            'ruc' => $personaJuridica->getRuc(),
            'representanteLegal' => $personaJuridica->getRepresentanteLegal()
        ];
    }
}   
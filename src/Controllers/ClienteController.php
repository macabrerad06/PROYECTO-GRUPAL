<?php declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Cliente;
use App\Repositories\ClienteRepository;
use Exception;

class ClienteController
{
    private ClienteRepository $clienteRepository;

    public function __construct()
    {
        $this->clienteRepository = new ClienteRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $cliente = $this->clienteRepository->findById((int)$_GET['id']);
                echo json_encode($cliente ? $this->clienteToArray($cliente) : null);
                return;
            } else {
                $list = array_map(
                    [$this, 'clienteToArray'],
                    $this->clienteRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            try {
                $cliente = new Cliente(
                    null,
                    $payload['id'],
                    $payload['email'],
                    $payload['telefono'],
                    $payload['direccion'],
                    $payload['tipoCliente']
                );
                echo json_encode(['success' => $this->clienteRepository->create($cliente)]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided: ' . $e->getMessage()]);
            }
            return;
        }

        if ($method === 'PUT') {
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->clienteaRepository->findById($id);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['error' => 'Cliente not found']);
                return;
            }

            if (isset($payload['email'])) $existing->setEmail($payload['email']);
            if (isset($payload['telefono'])) $existing->setTelefono($payload['telefono']);
            if (isset($payload['direccion'])) $existing->setDireccion($payload['direccion']);
            if (isset($payload['tipoCliente'])) $existing->setTipoCliente($payload['tipoCliente']);

            echo json_encode(['success' => $this->clienteRepository->update($existing)]);
            return;
        }

        if ($method === 'DELETE') {
            $id = (int)($payload['id'] ?? 0);
            if ($id === 0) {
                http_response_code(400);
                echo json_encode(['error' => 'ID not provided']);
                return;
            }
            echo json_encode(['success' => $this->clienteRepository->delete($id)]);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

    public function clienteToArray(Cliente $cliente): array
    {
        return [
            'id' => $cliente->getId(),
            'email' => $cliente->getEmail(),
            'telefono' => $cliente->getTelefono(),
            'direccion' => $cliente->getDireccion(),
            'tipoCliente' => $cliente->getTipoCliente()
        ];
    }
}
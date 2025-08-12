<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Cliente;
use PDO;

class ClienteRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Cliente) {
            throw new \InvalidArgumentException('Entity must be an instance of Cliente');
        }

        $sql = "INSERT INTO cliente (id, email, telefono, direccion,tipoCliente) 
        VALUES (:id, :email, :telefono, :direccion, :tipoCliente)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $entity->getId(),
            ':email' => $entity->getEmail(),
            ':telefono' => $entity->getTelefono(),
            ':direccion' => $entity->getDireccion(),
            ':tipoCliente' => $entity->getTipoCliente()
        ]);
    }

    public function findById(int $id): ?object
    {
        $sql = "SELECT * FROM cliente WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Cliente) {
            throw new \InvalidArgumentException('Cliente expected');
        }
        
        $sql = "UPDATE cliente SET 
                email=:email,
                telefono=:telefono,
                direccion=:direccion,
                tipoCliente=:tipoCliente WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $entity->getId(),
            'email' => $entity->getEmail(),
            'telefono' => $entity->getTelefono(),
            'direccion' => $entity->getDireccion(),
            'tipoCliente' => $entity->getTipoCliente()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM cliente WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM cliente";
        $stmt = $this->db->query($sql);
        $list = [];
        while ($row = $stmt->fetch()) {
            $list[] = $this->hydrate($row);
        }
        return $list;
    }

    private function hydrate(array $row): Cliente
    {
        return new Cliente(
            (int)$row['id'],
            $row['email'],
            $row['telefono'],
            $row['direccion'],
            $row['tipoCliente']
        );
    }
}
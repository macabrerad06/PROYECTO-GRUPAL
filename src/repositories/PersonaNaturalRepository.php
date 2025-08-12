<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\PersonaNatural;
use PDO;

class PersonaNaturalRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof PersonaNatural) {
            throw new \InvalidArgumentException('Entity must be an instance of PersonaNatural');
        }

        $sql = "INSERT INTO persona_natural (email, telefono, direccion, tipo_cliente, nombre, apellido, cedula) 
                VALUES (:email, :telefono, :direccion, :tipo_cliente, :nombre, :apellido, :cedula)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':email' => $entity->getEmail(),
            ':telefono' => $entity->getTelefono(),
            ':direccion' => $entity->getDireccion(),
            ':tipo_cliente' => $entity->getTipoCliente(),
            ':nombre' => $entity->getNombre(),
            ':apellido' => $entity->getApellido(),
            ':cedula' => $entity->getCedula()
        ]);
    }

    public function findById(int $id): ?object
    {
        $sql = "SELECT * FROM persona_natural WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof PersonaNatural) {
            throw new \InvalidArgumentException('Entity must be an instance of PersonaNatural');
        }

        $sql = "UPDATE persona_natural SET 
                email=:email,
                telefono=:telefono,
                direccion=:direccion,
                tipo_cliente=:tipo_cliente,
                nombre=:nombre,
                apellido=:apellido,
                cedula=:cedula WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $entity->getId(),
            'email' => $entity->getEmail(),
            'telefono' => $entity->getTelefono(),
            'direccion' => $entity->getDireccion(),
            'tipo_cliente' => $entity->getTipoCliente(),
            'nombre' => $entity->getNombre(),
            'apellido' => $entity->getApellido(),
            'cedula' => $entity->getCedula()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM persona_natural WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM persona_natural";
        $stmt = $this->db->query($sql);
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function hydrate(array $row): PersonaNatural
    {
        return new PersonaNatural(
            (int)$row['id'],
            $row['email'],
            $row['telefono'],
            $row['direccion'],
            $row['tipo_cliente'],
            $row['nombre'],
            $row['apellido'],
            $row['cedula']
        );
    }
}
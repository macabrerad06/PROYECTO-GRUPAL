<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\RolPermiso;
use PDO;

class RolPermisoRepository implements RepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof RolPermiso) {
            throw new InvalidArgumentException("Expected instance of RolPermiso");
        }

        $sql = "INSERT INTO rol_permiso (id_rol, id_permiso) VALUES (:id_rol, :id_permiso)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id_rol', $entity->getIdRol(), PDO::PARAM_INT);
        $stmt->bindValue(':id_permiso', $entity->getIdPermiso(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function findById(int $id): ?object
    {
        $sql = "SELECT * FROM rol_permiso WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new RolPermiso($data['id_rol'], $data['id_permiso']);
        }
        return null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof RolPermiso) {
            throw new InvalidArgumentException("Expected instance of RolPermiso");
        }

        if ($entity->getId() === null) {
            throw new InvalidArgumentException("Entity ID cannot be null for update");
        }

        $sql = "UPDATE rol_permiso SET id_rol = :id_rol, id_permiso = :id_permiso WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id_rol', $entity->getIdRol(), PDO::PARAM_INT);
        $stmt->bindValue(':id_permiso', $entity->getIdPermiso(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $entity->getId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM rol_permiso WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function findAll(): array
    {
        $sql = "SELECT * FROM rol_permiso";
        $stmt = $this->connection->query($sql);
        $results = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new RolPermiso($data['id_rol'], $data['id_permiso']);
        }
        return $results;
    }

    public function hydrate(array $data): RolPermiso
    {
        return new RolPermiso(
            (int)$data['id_rol'],
            (int)$data['id_permiso']
        );
    }
}
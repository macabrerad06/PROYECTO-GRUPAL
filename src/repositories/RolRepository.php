<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Rol;
use PDO;

class RolRepository implements RepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Rol) {
            throw new \InvalidArgumentException('Expected instance of Rol');
        }

        $stmt = $this->connection->prepare('INSERT INTO rol (id, nombre) VALUES (:id, :nombre)');
        $stmt->bindValue(':id', $entity->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':nombre', $entity->getNombre(), PDO::PARAM_STR);

        if ($stmt->execute()) {
            $entity->setId((int)$this->connection->lastInsertId());
            return true;
        }

        return false;
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->connection->prepare('SELECT * FROM rol WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $rol = new Rol($data['nombre']);
            $rol->setId((int)$data['id']);
            return $rol;
        }

        return null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Rol || $entity->getId() === null) {
            throw new \InvalidArgumentException('Expected instance of Rol with valid ID');
        }

        $stmt = $this->connection->prepare('UPDATE rol SET nombre = :nombre WHERE id = :id');
        $stmt->bindValue(':nombre', $entity->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $entity->getId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM rol WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query('SELECT * FROM rol');
        $roles = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rol = new Rol($data['nombre']);
            $rol->setId((int)$data['id']);
            $roles[] = $rol;
        }

        return $roles;
    }

    public function hydrate(array $data): Rol
    {
        $rol = new Rol($data['nombre']);
        $rol->setId((int)$data['id']);
        return $rol;
    }
}
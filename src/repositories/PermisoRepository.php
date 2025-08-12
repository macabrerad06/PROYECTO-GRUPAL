<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Permiso;
use PDO;

class PermisoRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Permiso) {
            throw new \InvalidArgumentException('Entity must be an instance of Permiso');
        }

        $sql = "INSERT INTO permiso (id, codigo) VALUES (:id, :codigo)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $entity->getId(),
            ':codigo' => $entity->getCodigo()
        ]);
    }

    public function findById(int $id): ?object
    {
        $sql = "SELECT * FROM permiso WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Permiso) {
            throw new \InvalidArgumentException('Permiso expected');
        }

        $sql = "UPDATE permiso SET codigo=:codigo WHERE id=:id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $entity->getId(),
            ':codigo' => $entity->getCodigo()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM permiso WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM permiso";
        $stmt = $this->db->query($sql);
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function hydrate(array $row): Permiso
    {
        return new Permiso(
            (int)$row['id'],
            $row['codigo']
        );
    }
}
<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Usuario;
use PDO;

class UsuarioRepository implements RepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Usuario) {
            throw new \InvalidArgumentException('Expected instance of Usuario');
        }

        $stmt = $this->connection->prepare('INSERT INTO usuario (id,username,password_hash,estado) VALUES (:id, :username, :password_hash, :estado)');
        $stmt->bindValue(':id', $entity->getId());
        $stmt->bindValue(':username', $entity->getUsername());
        $stmt->bindValue(':password_hash', password_hash($entity->getPasswordHash(), PASSWORD_BCRYPT));
        $stmt->bindValue(':estado', $entity->getEstado());

        return $stmt->execute();
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->connection->prepare('SELECT * FROM usuario WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Usuario($data['username'], $data['password_hash'], $data['estado'], (int)$data['id']);
        }

        return null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Usuario) {
            throw new \InvalidArgumentException('Expected instance of Usuario');
        }

        $stmt = $this->connection->prepare('UPDATE usuario SET username = :username, password_hash = :password_hash, estado = :estado WHERE id = :id');
        $stmt->bindValue(':username', $entity->getUsername());
        $stmt->bindValue(':password_hash', password_hash($entity->getPasswordHash(), PASSWORD_BCRYPT));
        $stmt->bindValue(':estado', $entity->getEstado());
        $stmt->bindValue(':id', $entity->getId());

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM usuario WHERE id = :id');
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query('SELECT * FROM usuario');
        $users = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new Usuario($data['username'], $data['password_hash'], $data['estado'], (int)$data['id']);
        }

        return $users;
    }

    public function hydrate(array $data): Usuario
    {
        return new Usuario(
            $data['username'],
            $data['password_hash'],
            $data['estado'],
            (int)$data['id']
        );
    }
}
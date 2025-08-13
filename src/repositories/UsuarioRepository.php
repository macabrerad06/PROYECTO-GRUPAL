<?php
declare(strict_types=1);

namespace App\Repositories; // Asegúrate de que esta capitalización sea correcta

use App\Interfaces\RepositoryInterface;
use App\Config\Database;
use App\Entities\Usuario; // Asegúrate de que esta capitalización sea correcta
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
        $stmt->bindValue(':estado', $entity->getEstado(), PDO::PARAM_BOOL); // <-- Usar PDO::PARAM_BOOL para el estado

        return $stmt->execute();
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->connection->prepare('SELECT * FROM usuario WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            // Asegúrate de que el estado sea bool y el ID sea int
            return new Usuario($data['username'], $data['password_hash'], (bool)$data['estado'], (int)$data['id']); // <-- Corrección aquí
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
        $stmt->bindValue(':estado', $entity->getEstado(), PDO::PARAM_BOOL); // <-- Usar PDO::PARAM_BOOL para el estado
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
        $usuarios = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Asegúrate de que el estado sea bool y el ID sea int
            $usuarios[] = new Usuario($data['username'], $data['password_hash'], (bool)$data['estado'], (int)$data['id']); // <-- Corrección aquí
        }

        return $usuarios;
    }

    // Este método hydrate() probablemente es llamado por array_map en findAll(),
    // pero si findAll() ya itera y crea objetos, hydrate() podría no ser necesario o estar duplicado.
    // Si se usa, la corrección es la misma:
    public function hydrate(array $data): Usuario
    {
        return new Usuario(
            $data['username'],
            $data['password_hash'],
            (bool)$data['estado'], // <-- Corrección aquí
            (int)$data['id']
        );
    }
}

<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Producto;
use PDO;

class ProductoRepository implements RepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Producto) {
            throw new \InvalidArgumentException('Entity must be an instance of Producto');
        }

        $sql = "INSERT INTO producto (id, nombre, descripcion, precioUnitario, stock, idCategoria, tipoProducto) 
                VALUES (:id, :nombre, :descripcion, :precioUnitario, :stock, :idCategoria, :tipoProducto)";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id' => $entity->getId(),
            ':nombre' => $entity->getNombre(),
            ':descripcion' => $entity->getDescripcion(),
            ':precioUnitario' => $entity->getPrecioUnitario(),
            ':stock' => $entity->getStock(),
            ':idCategoria' => $entity->getIdCategoria(),
            ':tipoProducto' => $entity->getTipoProducto()
        ]);
    }

    public function findById(int $id): ?object
    {
        $sql = "SELECT * FROM producto WHERE id=:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    private function hydrate(array $data): Producto
    {
        return new Producto(
            (int)$data['id'],
            $data['nombre'],
            $data['descripcion'],
            (float)$data['precioUnitario'],
            (int)$data['stock'],
            (int)$data['idCategoria'],
            $data['tipoProducto']
        );
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Producto) {
            throw new \InvalidArgumentException('Entity must be an instance of Producto');
        }

        $sql = "UPDATE producto SET nombre=:nombre, descripcion=:descripcion, precioUnitario=:precioUnitario, stock=:stock, idCategoria=:idCategoria, tipoProducto=:tipoProducto WHERE id=:id";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id' => $entity->getId(),
            ':nombre' => $entity->getNombre(),
            ':descripcion' => $entity->getDescripcion(),
            ':precioUnitario' => $entity->getPrecioUnitario(),
            ':stock' => $entity->getStock(),
            ':idCategoria' => $entity->getIdCategoria(),
            ':tipoProducto' => $entity->getTipoProducto(),
            ':id' => $entity->getId()
        ]);
    }

    public function delete(object $entity): bool
    {
        if (!$entity instanceof Producto) {
            throw new \InvalidArgumentException('Entity must be an instance of Producto');
        }

        $sql = "DELETE FROM producto WHERE id=:id";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([':id' => $entity->getId()]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM producto";
        $stmt = $this->connection->query($sql);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }
}

<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\ProductoFisico;
use PDO;

class ProductoFisicoRepository implements RepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof ProductoFisico) {
            throw new \InvalidArgumentException('Entity must be an instance of ProductoFisico');
        }

        $sql = "INSERT INTO producto_fisico (id_producto,nombre, descripcion, precioUnitario, stock, idCategoria, peso, alto, ancho, profundidad) 
                VALUES (:id_producto, :nombre, :descripcion, :precioUnitario, :stock, :idCategoria, :peso, :alto, :ancho, :profundidad)";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id_producto' => $entity->getId(),
            ':nombre' => $entity->getNombre(),
            ':descripcion' => $entity->getDescripcion(),
            ':precioUnitario' => $entity->getPrecioUnitario(),
            ':stock' => $entity->getStock(),
            ':idCategoria' => $entity->getIdCategoria(),
            ':peso' => $entity->getPeso(),
            ':alto' => $entity->getAlto(),
            ':ancho' => $entity->getAncho(),
            ':profundidad' => $entity->getProfundidad()
        ]);
    }

    public function findById(int $id): ?object
    {
        $sql = "SELECT * FROM producto_fisico WHERE id_producto=:id_producto";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id_producto' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }


    public function update(object $entity): bool
    {
        if (!$entity instanceof ProductoFisico) {
            throw new \InvalidArgumentException('Entity must be an instance of ProductoFisico');
        }

        $sql = "UPDATE producto_fisico SET nombre=:nombre, descripcion=:descripcion, precioUnitario=:precioUnitario, stock=:stock, idCategoria=:idCategoria, peso=:peso, alto=:alto, ancho=:ancho, profundidad=:profundidad WHERE id=:id";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id_producto' => $entity->getId(),
            ':nombre' => $entity->getNombre(),
            ':descripcion' => $entity->getDescripcion(),
            ':precioUnitario' => $entity->getPrecioUnitario(),
            ':stock' => $entity->getStock(),
            ':idCategoria' => $entity->getIdCategoria(),
            ':peso' => $entity->getPeso(),
            ':alto' => $entity->getAlto(),
            ':ancho' => $entity->getAncho(),
            ':profundidad' => $entity->getProfundidad(),
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM producto_fisico WHERE id_producto=:id_producto";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([':id_producto' => $id]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM producto_fisico";
        $stmt = $this->connection->query($sql);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }

    private function hydrate(array $row): ProductoFisico
    {
        return new ProductoFisico(
            (int)($row['id_producto'] ?? 0),           // Argumento #1: id
            (string)($row['nombre'] ?? ''),   // Argumento #2: nombre
            (string)($row['descripcion'] ?? ''),
            (float)($row['precio_unitario'] ?? 0.0),
            (int)($row['stock'] ?? 0),
            (int)($row['id_categoria'] ?? 0),
            (float)($row['peso'] ?? 0.0),
            (float)($row['alto'] ?? 0.0),
            (float)($row['ancho'] ?? 0.0),
            (float)($row['profundidad'] ?? 0.0)
        );
    }
}
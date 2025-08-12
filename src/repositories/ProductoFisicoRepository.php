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

        $sql = "INSERT INTO producto_fisico (nombre, descripcion, precioUnitario, stock, idCategoria, peso, alto, ancho, profundidad) 
                VALUES (:nombre, :descripcion, :precioUnitario, :stock, :idCategoria, :peso, :alto, :ancho, :profundidad)";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
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
        $sql = "SELECT * FROM producto_fisico WHERE id=:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);
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
            ':nombre' => $entity->getNombre(),
            ':descripcion' => $entity->getDescripcion(),
            ':precioUnitario' => $entity->getPrecioUnitario(),
            ':stock' => $entity->getStock(),
            ':idCategoria' => $entity->getIdCategoria(),
            ':peso' => $entity->getPeso(),
            ':alto' => $entity->getAlto(),
            ':ancho' => $entity->getAncho(),
            ':profundidad' => $entity->getProfundidad(),
            ':id' => $entity->getId()
        ]);
    }

    public function delete(object $entity): bool
    {
        if (!$entity instanceof ProductoFisico) {
            throw new \InvalidArgumentException('Entity must be an instance of ProductoFisico');
        }

        $sql = "DELETE FROM producto_fisico WHERE id=:id";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([':id' => $entity->getId()]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM producto_fisico";
        $stmt = $this->connection->query($sql);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }

    public function hydrate(array $data): ProductoFisico
    {
        return new ProductoFisico(
            id: (int)$data['id'],
            nombre: (string)$data['nombre'],
            descripcion: (string)$data['descripcion'],
            precioUnitario: (float)$data['precioUnitario'],
            stock: (int)$data['stock'],
            idCategoria: (int)$data['idCategoria'],
            peso: (float)$data['peso'],
            alto: (float)$data['alto'],
            ancho: (float)$data['ancho'],
            profundidad: (float)$data['profundidad']
        );
    }

}

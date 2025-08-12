<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\ProductoDigital;
use PDO;

class ProductoDigitalRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof ProductoDigital) {
            throw new \InvalidArgumentException('Entity must be an instance of ProductoDigital');
        }

        $sql = "INSERT INTO producto_digital (nombre, descripcion, precioUnitario, stock,idCategoria, urlDescarga, licencia) 
                VALUES (:nombre, :descripcion, :precioUnitario, :stock, :idCategoria, :urlDescarga, :licencia)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre' => $entity->getNombre(),
            ':descripcion' => $entity->getDescripcion(),
            ':precioUnitario' => $entity->getPrecioUnitario(),
            ':stock' => $entity->getStock(),
            ':idCategoria' => $entity->getIdCategoria(),
            ':urlDescarga' => $entity->getUrlDescarga(),
            ':licencia' => $entity->getLicencia()
        ]);
    }

    public function findById(int $id): ?object
    {
        $sql = "SELECT * FROM producto_digital WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof ProductoDigital) {
            throw new \InvalidArgumentException('Entity must be an instance of ProductoDigital');
        }

        $sql = "UPDATE producto_digital SET 
                nombre=:nombre,
                descripcion=:descripcion,
                precioUnitario=:precioUnitario,
                stock=:stock,
                idCategoria=:idCategoria,
                urlDescarga=:urlDescarga,
                licencia=:licencia WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':nombre' => $entity->getNombre(),
            ':descripcion' => $entity->getDescripcion(),
            ':precioUnitario' => $entity->getPrecioUnitario(),
            ':stock' => $entity->getStock(),
            ':idCategoria' => $entity->getIdCategoria(),
            ':urlDescarga' => $entity->getUrlDescarga(),
            ':licencia' => $entity->getLicencia(),
            ':id' => $entity->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM producto_digital WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM producto_digital";
        $stmt = $this->db->query($sql);
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function hydrate(array $row): ProductoDigital
    {
        return new ProductoDigital(
            (int)$row['id'],
            $row['nombre'],
            $row['descripcion'],
            (float)$row['precioUnitario'],
            (int)$row['stock'],
            (int)$row['idCategoria'],
            $row['urlDescarga'],
            $row['licencia']
        );
    }
}
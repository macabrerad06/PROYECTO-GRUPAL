<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\DetalleVenta;
use PDO;


class DetalleVentaRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof DetalleVenta) {
            throw new \InvalidArgumentException('Entity must be an instance of DetalleVenta');
        }

        $sql = "INSERT INTO detalle_venta (idVenta, lineNumber, idProducto, cantidad, precioUnitario, subtotal) 
                VALUES (:idVenta, :lineNumber, :idProducto, :cantidad, :precioUnitario, :subtotal)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':idVenta' => $entity->getIdVenta(),
            ':lineNumber' => $entity->getLineNumber(),
            ':idProducto' => $entity->getIdProducto(),
            ':cantidad' => $entity->getCantidad(),
            ':precioUnitario' => $entity->getPrecioUnitario(),
            ':subtotal' => $entity->getSubtotal()
        ]);
    }

    public function findById(int $id): ?object
    {
        $sql = "SELECT * FROM detalle_venta WHERE idVenta=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof DetalleVenta) {
            throw new \InvalidArgumentException('DetalleVenta expected');
        }
        
        $sql = "UPDATE detalle_venta SET 
                idProducto=:idProducto,
                cantidad=:cantidad,
                precioUnitario=:precioUnitario,
                subtotal=:subtotal WHERE idVenta=:idVenta AND lineNumber=:lineNumber";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':idProducto' => $entity->getIdProducto(),
            ':cantidad' => $entity->getCantidad(),
            ':precioUnitario' => $entity->getPrecioUnitario(),
            ':subtotal' => $entity->getSubtotal(),
            ':idVenta' => $entity->getIdVenta(),
            ':lineNumber' => $entity->getLineNumber(),
        ]);
    }

    public function delete(int $idVenta, int $lineNumber): bool
    {
        $sql = "DELETE FROM detalle_venta WHERE idVenta=:idVenta AND lineNumber=:lineNumber";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':idVenta' => $idVenta, ':lineNumber' => $lineNumber]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM detalle_venta";
        $stmt = $this->db->query($sql);
        $list = [];
        while ($row = $stmt->fetch()) {
            $list[] = $this->hydrate($row);
        }
        return $list;
    }

    private function hydrate(array $row): DetalleVenta
    {
        return new DetalleVenta(
            (int)$row['idVenta'],
            (int)$row['lineNumber'],
            (int)$row['idProducto'],
            (int)$row['cantidad'],
            (float)$row['precioUnitario'],
            (float)$row['subtotal']
        );
    }
}
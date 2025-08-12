<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Factura;
use PDO;

class FacturaRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Factura) {
            throw new \InvalidArgumentException('Entity must be an instance of Factura');
        }

        $sql = "INSERT INTO factura (id, idVenta, numero, claveAcceso, fechaEmision, estado) 
                VALUES (:id, :idVenta, :numero, :claveAcceso, :fechaEmision, :estado)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $entity->getId(),
            ':idVenta' => $entity->getIdVenta(),
            ':numero' => $entity->getNumero(),
            ':claveAcceso' => $entity->getClaveAcceso(),
            ':fechaEmision' => $entity->getFechaEmision(),
            ':estado' => $entity->getEstado()
        ]);
    }

    public function findById(int $id): ?object
    {
        $sql = "SELECT * FROM factura WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Factura) {
            throw new \InvalidArgumentException('Factura expected');
        }

        $sql = "UPDATE factura SET 
                idVenta=:idVenta,
                numero=:numero,
                claveAcceso=:claveAcceso,
                fechaEmision=:fechaEmision,
                estado=:estado WHERE id=:id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $entity->getId(),
            ':idVenta' => $entity->getIdVenta(),
            ':numero' => $entity->getNumero(),
            ':claveAcceso' => $entity->getClaveAcceso(),
            ':fechaEmision' => $entity->getFechaEmision(),
            ':estado' => $entity->getEstado()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM factura WHERE id=:id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM factura";
        $stmt = $this->db->query($sql);
        $list = [];
        while ($row = $stmt->fetch()) {
            $list[] = $this->hydrate($row);
        }
        return $list;
    }

    private function hydrate(array $row): Factura
    {
        return new Factura(
            (int)$row['id'],
            (int)$row['id_venta'],
            (string)$row['numero'],
            (string)$row['clave_acceso'],
            new \DateTimeImmutable($row['fecha_emision']),
            (string)$row['estado']
        );
    }
}
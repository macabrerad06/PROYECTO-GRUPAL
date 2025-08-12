<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Venta;
use PDO;

class VentaRepository implements RepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Venta) {
            throw new \InvalidArgumentException('Expected instance of Venta');
        }

        $stmt = $this->connection->prepare('INSERT INTO venta (id,fecha,idCliente,total,estado) VALUES (:id, :fecha, :idCliente, :total, :estado)');
        $stmt->bindValue(':id', $entity->getId());
        $stmt->bindValue(':fecha', $entity->getFecha()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':idCliente', $entity->getIdCliente());
        $stmt->bindValue(':total', $entity->getTotal());
        $stmt->bindValue(':estado', $entity->getEstado());

        return $stmt->execute();
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->connection->prepare('SELECT * FROM venta WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Venta(
                (int)$data['idCliente'],
                (float)$data['total'],
                $data['estado'],
                new \DateTime($data['fecha']),
                (int)$data['id']
            );
        }

        return null;
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query('SELECT * FROM venta');
        $ventas = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ventas[] = new Venta((int)$data['idCliente'], (float)$data['total'], $data['estado'], new \DateTime($data['fecha']), (int)$data['id']);
        }

        return $ventas;
    }

    public function hydrate(array $data): Venta
    {
        return new Venta(
            (int)$data['idCliente'],
            (float)$data['total'],
            $data['estado'],
            new \DateTime($data['fecha']),
            (int)$data['id']
        );
    }
    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM venta WHERE id = :id');
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Venta) {
            throw new \InvalidArgumentException('Expected instance of Venta');
        }

        $stmt = $this->connection->prepare('UPDATE venta SET idCliente = :idCliente, total = :total, estado = :estado, fecha = :fecha WHERE id = :id');
        $stmt->bindValue(':idCliente', $entity->getIdCliente());
        $stmt->bindValue(':total', $entity->getTotal());
        $stmt->bindValue(':estado', $entity->getEstado());
        $stmt->bindValue(':fecha', $entity->getFecha()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':id', $entity->getId());

        return $stmt->execute();
    }
}
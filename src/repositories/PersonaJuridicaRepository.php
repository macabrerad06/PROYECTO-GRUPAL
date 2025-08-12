<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\PersonaJuridica;
use PDO;

class PersonaJuridicaRepository implements RepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof PersonaJuridica) {
            throw new InvalidArgumentException("Entity must be of type PersonaJuridica.");
        }

        $stmt = $this->connection->prepare(
            "INSERT INTO persona_juridica (email, telefono, direccion, tipo_cliente, razon_social, ruc, representante_legal) 
             VALUES (:email, :telefono, :direccion, :tipo_cliente, :razon_social, :ruc, :representante_legal)"
        );

        return $stmt->execute([
            ':email' => $entity->getEmail(),
            ':telefono' => $entity->getTelefono(),
            ':direccion' => $entity->getDireccion(),
            ':tipo_cliente' => $entity->getTipoCliente(),
            ':razon_social' => $entity->getRazonSocial(),
            ':ruc' => $entity->getRuc(),
            ':representante_legal' => $entity->getRepresentanteLegal()
        ]);
    }

    public function findById(int $id): ?PersonaJuridica
    {
        $stmt = $this->connection->prepare("SELECT * FROM persona_juridica WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new PersonaJuridica(
            $data['email'],
            $data['telefono'],
            $data['direccion'],
            $data['tipo_cliente'],
            $data['razon_social'],
            $data['ruc'],
            $data['representante_legal']
        ) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof PersonaJuridica) {
            throw new InvalidArgumentException("Entity must be of type PersonaJuridica.");
        }

        $stmt = $this->connection->prepare(
            "UPDATE persona_juridica SET 
             email = :email, 
             telefono = :telefono, 
             direccion = :direccion, 
             tipo_cliente = :tipo_cliente, 
             razon_social = :razon_social, 
             ruc = :ruc, 
             representante_legal = :representante_legal 
             WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => $entity->getId(),
            ':email' => $entity->getEmail(),
            ':telefono' => $entity->getTelefono(),
            ':direccion' => $entity->getDireccion(),
            ':tipo_cliente' => $entity->getTipoCliente(),
            ':razon_social' => $entity->getRazonSocial(),
            ':ruc' => $entity->getRuc(),
            ':representante_legal' => $entity->getRepresentanteLegal()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM persona_juridica WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM persona_juridica");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($item) => new PersonaJuridica(
            $item['email'],
            $item['telefono'],
            $item['direccion'],
            $item['tipo_cliente'],
            $item['razon_social'],
            $item['ruc'],
            $item['representante_legal']
        ), $data);
    }

}
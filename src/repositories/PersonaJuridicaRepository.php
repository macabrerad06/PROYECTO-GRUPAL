<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Config\Database;
use App\Entities\PersonaJuridica;
use InvalidArgumentException;
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

        // Asumiendo que primero creas la Persona base y obtienes su ID
        // Aquí solo se inserta en persona_juridica, asumiendo que el ID ya existe en 'cliente'
        $stmt = $this->connection->prepare(
            "INSERT INTO persona_juridica (id_cliente, razon_social, ruc, representante_legal) 
             VALUES (:id_cliente, :razon_social, :ruc, :representante_legal)"
        );

        return $stmt->execute([
            ':id_cliente' => $entity->getId(), 
            ':razon_social' => $entity->getRazonSocial(),
            ':ruc' => $entity->getRuc(),
            ':representante_legal' => $entity->getRepresentanteLegal()
        ]);
    }

    public function findById(int $id): ?PersonaJuridica
    {
        // Realizamos un JOIN para obtener todos los datos de 'cliente' y 'persona_juridica'
        $stmt = $this->connection->prepare(
            "SELECT 
                c.id, c.email, c.telefono, c.direccion, c.tipo_cliente,
                pj.razon_social, pj.ruc, pj.representante_legal
            FROM 
                cliente AS c -- <-- ¡Aquí la corrección! Ahora es 'cliente'
            JOIN 
                persona_juridica AS pj ON c.id = pj.id_cliente
            WHERE 
                c.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si se encuentran datos, creamos la entidad con el orden correcto
        return $data ? new PersonaJuridica(
            $data['email'],
            $data['telefono'],
            $data['direccion'],
            (string) $data['tipo_cliente'], // <-- ¡CORRECCIÓN! Ahora es (string)
            $data['razon_social'],
            $data['ruc'],
            $data['representante_legal'],
            (int) $data['id'] // Pasamos el ID al final si el constructor lo acepta
        ) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof PersonaJuridica) {
            throw new InvalidArgumentException("Entity must be of type PersonaJuridica.");
        }

        // Actualizar primero los campos de la tabla 'cliente'
        $stmtCliente = $this->connection->prepare(
            "UPDATE cliente SET -- <-- ¡Aquí la corrección! Ahora es 'cliente'
                email = :email,
                telefono = :telefono,
                direccion = :direccion,
                tipo_cliente = :tipo_cliente
            WHERE id = :id"
        );
        $updatedCliente = $stmtCliente->execute([
            ':email' => $entity->getEmail(),
            ':telefono' => $entity->getTelefono(),
            ':direccion' => $entity->getDireccion(),
            ':tipo_cliente' => $entity->getTipoCliente(),
            ':id' => $entity->getId()
        ]);

        // Luego, actualizar los campos específicos de 'persona_juridica'
        $stmtJuridica = $this->connection->prepare(
            "UPDATE persona_juridica SET 
                razon_social = :razon_social,
                ruc = :ruc,
                representante_legal = :representante_legal
            WHERE id_cliente = :id_cliente"
        );
        $updatedJuridica = $stmtJuridica->execute([
            ':id_cliente' => $entity->getId(),
            ':razon_social' => $entity->getRazonSocial(),
            ':ruc' => $entity->getRuc(),
            ':representante_legal' => $entity->getRepresentanteLegal()
        ]);

        return $updatedCliente && $updatedJuridica;
    }

    public function delete(int $id): bool
    {
        // Primero eliminamos de persona_juridica (por la FK)
        $stmtJuridica = $this->connection->prepare("DELETE FROM persona_juridica WHERE id_cliente = :id_cliente");
        $deletedJuridica = $stmtJuridica->execute([':id_cliente' => $id]);

        // Luego eliminamos de cliente
        $stmtCliente = $this->connection->prepare("DELETE FROM cliente WHERE id = :id"); // <-- ¡Aquí la corrección!
        $deletedCliente = $stmtCliente->execute([':id' => $id]);

        return $deletedJuridica && $deletedCliente;
    }

    public function findAll(): array
    {
        // Realizamos un JOIN para obtener todos los datos necesarios de ambas tablas
        $stmt = $this->connection->query(
            "SELECT 
                c.id, c.email, c.telefono, c.direccion, c.tipo_cliente,
                pj.razon_social, pj.ruc, pj.representante_legal
            FROM 
                cliente AS c -- <-- ¡Aquí la corrección! Ahora es 'cliente'
            JOIN 
                persona_juridica AS pj ON c.id = pj.id_cliente"
        );
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($item) => new PersonaJuridica(
            $item['email'],
            $item['telefono'],
            $item['direccion'],
            (string) $item['tipo_cliente'], // <-- ¡CORRECCIÓN! Ahora es (string)
            $item['razon_social'],
            $item['ruc'],
            $item['representante_legal'],
            (int) $item['id']
        ), $data);
    }
}

<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Config\Database;
use App\Entities\PersonaNatural;
use PDO;

class PersonaNaturalRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof PersonaNatural) {
            throw new InvalidArgumentException('Entity must be an instance of PersonaNatural');
        }

        // Aquí deberías tener lógica para insertar primero en la tabla 'cliente'
        // y obtener el id_cliente si la entidad PersonaNatural no lo tiene ya.
        // Por ahora, asumimos que getId() en PersonaNatural devuelve el id_cliente válido.

        $sql = "INSERT INTO persona_natural (id_cliente, nombres, apellidos, cedula) 
                VALUES (:id_cliente, :nombres, :apellidos, :cedula)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_cliente' => $entity->getId(), // Asume que getId() devuelve el id de la tabla 'cliente'
            ':nombres' => $entity->getNombre(),
            ':apellidos' => $entity->getApellido(),
            ':cedula' => $entity->getCedula()
        ]);
    }

    public function findById(int $id): ?PersonaNatural // El tipo de retorno debe ser ?PersonaNatural
    {
        $sql = "SELECT 
                    c.id, c.email, c.telefono, c.direccion, c.tipo_cliente,
                    pn.nombres, pn.apellidos, pn.cedula
                FROM 
                    cliente AS c
                JOIN 
                    persona_natural AS pn ON c.id = pn.id_cliente
                WHERE c.id = :id_cliente"; // Asegúrate de que la columna id en la tabla cliente se llama 'id'

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_cliente' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // Usar FETCH_ASSOC para nombres de columna

        return $row ? $this->hydrate($row) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof PersonaNatural) {
            throw new InvalidArgumentException('Entity must be an instance of PersonaNatural');
        }

        // 1. Actualizar la tabla 'cliente'
        $sqlCliente = "UPDATE cliente SET 
                        email = :email,
                        telefono = :telefono,
                        direccion = :direccion,
                        tipo_cliente = :tipo_cliente
                       WHERE id = :id";
        $stmtCliente = $this->db->prepare($sqlCliente);
        $updatedCliente = $stmtCliente->execute([
            ':email' => $entity->getEmail(),
            ':telefono' => $entity->getTelefono(),
            ':direccion' => $entity->getDireccion(),
            ':tipo_cliente' => $entity->getTipoCliente(),
            ':id' => $entity->getId()
        ]);

        // 2. Actualizar la tabla 'persona_natural'
        $sqlNatural = "UPDATE persona_natural SET 
                       nombres = :nombres,
                       apellidos = :apellidos,
                       cedula = :cedula
                       WHERE id_cliente = :id_cliente";
        $stmtNatural = $this->db->prepare($sqlNatural);
        $updatedNatural = $stmtNatural->execute([
            ':nombres' => $entity->getNombre(),
            ':apellidos' => $entity->getApellido(),
            ':cedula' => $entity->getCedula(),
            ':id_cliente' => $entity->getId()
        ]);
        
        return $updatedCliente && $updatedNatural;
    }

    public function delete(int $id): bool
    {
        // 1. Eliminar de persona_natural (tabla hija)
        $sqlNatural = "DELETE FROM persona_natural WHERE id_cliente = :id_cliente";
        $stmtNatural = $this->db->prepare($sqlNatural);
        $deletedNatural = $stmtNatural->execute([':id_cliente' => $id]);

        // 2. Eliminar de cliente (tabla padre)
        $sqlCliente = "DELETE FROM cliente WHERE id = :id";
        $stmtCliente = $this->db->prepare($sqlCliente);
        $deletedCliente = $stmtCliente->execute([':id' => $id]);

        return $deletedNatural && $deletedCliente;
    }

    public function findAll(): array
    {
        // Modificamos la consulta para hacer un JOIN y obtener todos los campos
        $sql = "SELECT 
                    c.id, c.email, c.telefono, c.direccion, c.tipo_cliente,
                    pn.nombres, pn.apellidos, pn.cedula
                FROM 
                    cliente AS c 
                JOIN 
                    persona_natural AS pn ON c.id = pn.id_cliente";
        $stmt = $this->db->query($sql);
        
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function hydrate(array $row): PersonaNatural
    {
        // Aquí es donde se construye el objeto PersonaNatural.
        // El orden y los tipos de los argumentos DEBEN coincidir con el constructor de PersonaNatural.
        // Basándonos en el último error, el constructor espera: $email, $telefono, $direccion, $tipoCliente, $nombre, $apellido, $cedula, $id
        return new PersonaNatural(
            (string) ($row['email'] ?? ''),        // Argumento #1: email (string)
            (string) ($row['telefono'] ?? ''),     // Argumento #2: telefono (string)
            (string) ($row['direccion'] ?? ''),    // Argumento #3: direccion (string)
            (string) ($row['tipo_cliente'] ?? ''), // Argumento #4: tipo_cliente (string)
            (string) ($row['nombres'] ?? ''),      // Argumento #5: nombres (string)
            (string) ($row['apellidos'] ?? ''),    // Argumento #6: apellidos (string)
            (string) ($row['cedula'] ?? ''),       // Argumento #7: cedula (string)
            (int) ($row['id'] ?? 0)               // Argumento #8: id (int)
        );
    }
}

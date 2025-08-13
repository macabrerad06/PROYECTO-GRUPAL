<?php

declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Categoria;
use PDO;

class CategoriaRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Categoria) {
            throw new \InvalidArgumentException('Entity must be an instance of Categoria');
        }

        $sql = "INSERT INTO categoria (id, nombre, descripcion, estado, id_padre) 
        VALUES (:id, :nombre, :descripcion, :estado, :id_padre)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $entity->getId(),
            ':nombre' => $entity->getNombre(),
            ':descripcion' => $entity->getDescripcion(),
            ':estado' => $entity->getEstado(),
            ':id_padre' => $entity->getIdPadre()
        ]);
    }

    public function findById(int $id): ?object{
        $sql = "SELECT * FROM categoria WHERE id=:id";
        $stmt = $this -> db -> prepare($sql);
        $stmt -> execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this -> hydrate($row) : null;
    }

    public function update(object $entity): bool{
        if(!$entity instanceof Categoria){
            throw new \InvalidArgumentException('Categoria expected');
        }
        $sql = "UPDATE categoria SET 
                nombre=:nombre,
                descripcion=:descripcion,
                estado=:estado,
                id_padre=:id_padre WHERE id=:id";
        $stmt = $this -> db -> prepare($sql);
        return $stmt -> execute([
            'id' => $entity -> getId(),
            'nombre' => $entity -> getNombre(),
            'descripcion' => $entity -> getDescripcion(),
            'estado' => $entity -> getEstado(),
            'id_padre' => $entity -> getIdPadre()
        ]);
        
    }

    public function delete(int $id): bool{
        $sql = "DELETE FROM categoria WHERE id=:id";
        $stmt = $this -> db -> prepare($sql);
        return $stmt -> execute([
            ':id' => $id
        ]);
    }

    public function findAll(): array{
        $stmt = $this -> db -> query("SELECT * FROM categoria");
        $list=[];
        while ($row = $stmt->fetch()){
            $list[]=$this->hydrate($row);
        }
        return $list;

    }

    private function hydrate(array $row): Categoria{
        $categoria = new Categoria(
            $row['id'],
            $row['nombre'],
            $row['descripcion'],
            (bool)$row['estado'],
            $row['id_padre']
        );
        return $categoria;
    }

}
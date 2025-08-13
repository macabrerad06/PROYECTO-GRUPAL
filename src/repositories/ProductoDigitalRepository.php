<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Config\Database;
use App\Entities\ProductoDigital;
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
            throw new InvalidArgumentException('Entity must be an instance of ProductoDigital');
        }

        // 1. Insertar en la tabla 'producto' (datos generales del producto)
        $sqlProducto = "INSERT INTO producto (nombre, descripcion, precio_unitario, stock, id_categoria, tipo_producto) 
                        VALUES (:nombre, :descripcion, :precio_unitario, :stock, :id_categoria, :tipo_producto)";
        $stmtProducto = $this->db->prepare($sqlProducto);

        $insertedProducto = $stmtProducto->execute([
            ':nombre' => $entity->getNombre(),
            ':descripcion' => $entity->getDescripcion(),
            ':precio_unitario' => $entity->getPrecioUnitario(),
            ':stock' => $entity->getStock(),
            ':id_categoria' => $entity->getIdCategoria(),
            ':tipo_producto' => 'DIGITAL' // Este tipo es fijo para este repositorio
        ]);

        if (!$insertedProducto) {
            return false;
        }

        // Obtener el ID del producto recién insertado
        $idProducto = (int)$this->db->lastInsertId();
        $entity->setId($idProducto); // Asignar el ID a la entidad

        // 2. Insertar en la tabla 'producto_digital' (datos específicos del producto digital)
        $sqlDigital = "INSERT INTO producto_digital (id_producto, url_descarga, licencia) 
                       VALUES (:id_producto, :url_descarga, :licencia)";
        $stmtDigital = $this->db->prepare($sqlDigital);

        return $stmtDigital->execute([
            ':id_producto' => $idProducto,
            ':url_descarga' => $entity->getUrlDescarga(),
            ':licencia' => $entity->getLicencia()
        ]);
    }

    public function findById(int $id): ?ProductoDigital
    {
        // Realizamos un JOIN para obtener todos los datos de 'producto' y 'producto_digital'
        $sql = "SELECT 
                    p.id, p.nombre, p.descripcion, p.precio_unitario, p.stock, p.id_categoria, p.tipo_producto,
                    pd.url_descarga, pd.licencia
                FROM 
                    producto AS p
                JOIN 
                    producto_digital AS pd ON p.id = pd.id_producto
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // Usar FETCH_ASSOC para nombres de columna

        return $row ? $this->hydrate($row) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof ProductoDigital) {
            throw new InvalidArgumentException('Entity must be an instance of ProductoDigital');
        }

        // 1. Actualizar la tabla 'producto'
        $sqlProducto = "UPDATE producto SET 
                            nombre = :nombre,
                            descripcion = :descripcion,
                            precio_unitario = :precio_unitario,
                            stock = :stock,
                            id_categoria = :id_categoria
                        WHERE id = :id";
        $stmtProducto = $this->db->prepare($sqlProducto);

        $updatedProducto = $stmtProducto->execute([
            ':nombre' => $entity->getNombre(),
            ':descripcion' => $entity->getDescripcion(),
            ':precio_unitario' => $entity->getPrecioUnitario(),
            ':stock' => $entity->getStock(),
            ':id_categoria' => $entity->getIdCategoria(),
            ':id' => $entity->getId()
        ]);

        // 2. Actualizar la tabla 'producto_digital'
        $sqlDigital = "UPDATE producto_digital SET 
                           url_descarga = :url_descarga,
                           licencia = :licencia
                       WHERE id_producto = :id_producto";
        $stmtDigital = $this->db->prepare($sqlDigital);
        
        $updatedDigital = $stmtDigital->execute([
            ':url_descarga' => $entity->getUrlDescarga(),
            ':licencia' => $entity->getLicencia(),
            ':id_producto' => $entity->getId() // Usamos el ID del producto
        ]);
        
        return $updatedProducto && $updatedDigital;
    }

    public function delete(int $id): bool
    {
        // 1. Eliminar de producto_digital (tabla hija)
        $sqlDigital = "DELETE FROM producto_digital WHERE id_producto = :id_producto";
        $stmtDigital = $this->db->prepare($sqlDigital);
        $deletedDigital = $stmtDigital->execute([':id_producto' => $id]);

        // 2. Eliminar de producto (tabla padre), ya que producto_digital tiene ON DELETE CASCADE
        // No necesitamos eliminar manualmente de 'producto' si tienes ON DELETE CASCADE,
        // pero lo dejo para que sea explícito y consistente si cambias la FK.
        // Si CASCADE funciona, esta parte es opcional.
        $sqlProducto = "DELETE FROM producto WHERE id = :id";
        $stmtProducto = $this->db->prepare($sqlProducto);
        $deletedProducto = $stmtProducto->execute([':id' => $id]);

        return $deletedDigital && $deletedProducto;
    }

    public function findAll(): array
    {
        // Realizamos un JOIN para obtener todos los datos necesarios de ambas tablas
        $sql = "SELECT 
                    p.id, p.nombre, p.descripcion, p.precio_unitario, p.stock, p.id_categoria, p.tipo_producto,
                    pd.url_descarga, pd.licencia
                FROM 
                    producto AS p 
                JOIN 
                    producto_digital AS pd ON p.id = pd.id_producto
                WHERE p.tipo_producto = 'DIGITAL'"; // Filtramos solo productos digitales
        $stmt = $this->db->query($sql);
        
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function hydrate(array $row): ProductoDigital
    {
        // El orden y los tipos de los argumentos DEBEN coincidir con el constructor de ProductoDigital.
        // Asumo que el constructor de ProductoDigital hereda de Producto y tiene estos argumentos:
        // __construct(string $nombre, string $descripcion, float $precioUnitario, int $stock, int $idCategoria, string $urlDescarga, string $licencia, ?int $id = null)
        return new ProductoDigital(
            (string) ($row['nombre'] ?? ''),
            (string) ($row['descripcion'] ?? ''),
            (float) ($row['precio_unitario'] ?? 0.0), // 'precio_unitario' con guion bajo
            (int) ($row['stock'] ?? 0),
            (int) ($row['id_categoria'] ?? 0), // 'id_categoria' con guion bajo
            (string) ($row['url_descarga'] ?? ''),
            (string) ($row['licencia'] ?? ''),
            (int) ($row['id'] ?? 0) // El ID suele ser el último argumento y es opcional
        );
    }
}

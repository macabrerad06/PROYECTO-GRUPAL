<?php

declare(strict_types=1);

namespace App\Entities;

// Asume que ProductoDigital extiende de Producto y que Producto tiene un constructor
// que acepta el ID como su primer argumento (?int $id = null)
class ProductoDigital extends Producto
{
    private string $urlDescarga;
    private string $licencia;

    public function __construct(
        string $nombre,
        ?string $descripcion,
        float $precioUnitario,
        int $stock,
        int $idCategoria,
        string $urlDescarga,
        string $licencia,
        ?int $id = null // <-- ¡HE AÑADIDO ESTE PARÁMETRO! Ahora el ID es aceptado
    ) {
        // Llama al constructor de la clase padre (Producto).
        // Los argumentos que pasas aquí DEBEN COINCIDIR con la firma del constructor de Producto.
        parent::__construct(
            $id, // <-- Pasa el $id que recibiste en este constructor
            $nombre,
            $descripcion,
            $precioUnitario,
            $stock,
            $idCategoria,
            'DIGITAL' // El tipo_producto es fijo para ProductoDigital
        );

        // Asigna las propiedades específicas de ProductoDigital
        $this->urlDescarga = $urlDescarga;
        $this->licencia = $licencia;
    }

    public function getUrlDescarga(): string { return $this->urlDescarga; }
    public function getLicencia(): string { return $this->licencia; }

    public function setUrlDescarga(string $urlDescarga): void { $this->urlDescarga = $urlDescarga; }
    public function setLicencia(string $licencia): void { $this->licencia = $licencia; }
}

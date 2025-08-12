<?php

declare(strict_types=1);

class Producto
{
    private ?int $id;
    private string $nombre;
    private ?string $descripcion; 
    private float $precioUnitario; 
    private int $stock;
    private int $idCategoria;
    private string $tipoProducto; 

    public function __construct(
        string $nombre,
        ?string $descripcion, 
        float $precioUnitario, 
        int $stock,
        int $idCategoria,
        string $tipoProducto
    ) {
        if ($precioUnitario < 0) {
            throw new InvalidArgumentException("El precio unitario no puede ser negativo.");
        }
        if ($stock < 0) {
            throw new InvalidArgumentException("El stock no puede ser negativo.");
        }
        if (!in_array($tipoProducto, ['FISICO', 'DIGITAL'], true)) {
            throw new InvalidArgumentException("El tipo de producto debe ser 'FISICO' o 'DIGITAL'.");
        }

        $this->id = null;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precioUnitario = $precioUnitario;
        $this->stock = $stock;
        $this->idCategoria = $idCategoria;
        $this->tipoProducto = $tipoProducto;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getDescripcion(): ?string 
    {
        return $this->descripcion;
    }
    public function getPrecioUnitario(): float
    {
        return $this->precioUnitario;
    }
    public function getStock(): int
    {
        return $this->stock;
    }
    public function getIdCategoria(): int
    {
        return $this->idCategoria;
    }
    public function getTipoProducto(): string 
    {
        return $this->tipoProducto;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
    public function setDescripcion(?string $descripcion): void 
    {
        $this->descripcion = $descripcion;
    }
    public function setPrecioUnitario(float $precioUnitario): void
    {
        if ($precioUnitario < 0) {
            throw new InvalidArgumentException("El precio unitario no puede ser negativo.");
        }
        $this->precioUnitario = $precioUnitario;
    }
    public function setStock(int $stock): void
    {
        if ($stock < 0) {
            throw new InvalidArgumentException("El stock no puede ser negativo.");
        }
        $this->stock = $stock;
    }
    public function setIdCategoria(int $idCategoria): void
    {
        $this->idCategoria = $idCategoria;
    }
    public function setTipoProducto(string $tipoProducto): void
    {
        if (!in_array($tipoProducto, ['FISICO', 'DIGITAL'], true)) {
            throw new InvalidArgumentException("El tipo de producto debe ser 'FISICO' o 'DIGITAL'.");
        }
        $this->tipo = $tipoProducto;
    }
}
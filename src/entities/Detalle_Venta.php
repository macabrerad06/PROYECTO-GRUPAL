<?php

declare(strict_types=1);

class DetalleVenta
{
    private int $idVenta;
    private int $lineNumber;
    private int $idProducto;
    private int $cantidad;
    private float $precioUnitario;
    private float $subtotal;

    public function __construct(
        int $idVenta,
        int $lineNumber,
        int $idProducto,
        int $cantidad,
        float $precioUnitario,
        float $subtotal
    ) {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad debe ser mayor que 0.");
        }
        if ($precioUnitario < 0) {
            throw new InvalidArgumentException("El precio unitario no puede ser negativo.");
        }
        if ($subtotal < 0) {
            throw new InvalidArgumentException("El subtotal no puede ser negativo.");
        }

        $this->idVenta = $idVenta;
        $this->lineNumber = $lineNumber;
        $this->idProducto = $idProducto;
        $this->cantidad = $cantidad;
        $this->precioUnitario = $precioUnitario;
        $this->subtotal = $subtotal;
    }

    public function getIdVenta(): int { return $this->idVenta; }
    public function getLineNumber(): int { return $this->lineNumber; }
    public function getIdProducto(): int { return $this->idProducto; }
    public function getCantidad(): int { return $this->cantidad; }
    public function getPrecioUnitario(): float { return $this->precioUnitario; }
    public function getSubtotal(): float { return $this->subtotal; }

    public function setIdProducto(int $idProducto): void { $this->idProducto = $idProducto; }
    public function setCantidad(int $cantidad): void
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad debe ser mayor que 0.");
        }
        $this->cantidad = $cantidad;
    }
    public function setPrecioUnitario(float $precioUnitario): void
    {
        if ($precioUnitario < 0) {
            throw new InvalidArgumentException("El precio unitario no puede ser negativo.");
        }
        $this->precioUnitario = $precioUnitario;
    }
    public function setSubtotal(float $subtotal): void
    {
        if ($subtotal < 0) {
            throw new InvalidArgumentException("El subtotal no puede ser negativo.");
        }
        $this->subtotal = $subtotal;
    }
}
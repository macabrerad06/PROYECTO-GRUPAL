<?php

declare(strict_types=1);

namespace App\entities;

class Venta
{
    private ?int $id;
    private DateTimeImmutable $fecha;
    private int $idCliente;
    private float $total;
    private string $estado;

    public function __construct(
        int $idCliente,
        float $total,
        string $estado,
        ?DateTimeImmutable $fecha = null
    ) {
        if ($total < 0) {
            throw new InvalidArgumentException("El total de la venta no puede ser negativo.");
        }
        if (!in_array($estado, ['borrador', 'emitida', 'anulada'], true)) {
            throw new InvalidArgumentException("Estado de venta inválido: {$estado}.");
        }

        $this->id = null;
        $this->fecha = $fecha ?? new DateTimeImmutable();
        $this->idCliente = $idCliente;
        $this->total = $total;
        $this->estado = $estado;
    }

  

    public function getId(): ?int { return $this->id; }
    public function getFecha(): DateTimeImmutable { return $this->fecha; }
    public function getIdCliente(): int { return $this->idCliente; }
    public function getTotal(): float { return $this->total; }
    public function getEstado(): string { return $this->estado; }


    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setFecha(DateTimeImmutable $fecha): void { $this->fecha = $fecha; }
    public function setIdCliente(int $idCliente): void { $this->idCliente = $idCliente; }
    public function setTotal(float $total): void
    {
        if ($total < 0) {
            throw new InvalidArgumentException("El total de la venta no puede ser negativo.");
        }
        $this->total = $total;
    }
    public function setEstado(string $estado): void
    {
        if (!in_array($estado, ['borrador', 'emitida', 'anulada'], true)) {
            throw new InvalidArgumentException("Estado de venta inválido: {$estado}.");
        }
        $this->estado = $estado;
    }
}
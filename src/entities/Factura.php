<?php

declare(strict_types=1);

class Factura
{
    private ?int $id;
    private int $idVenta;
    private string $numero;
    private ?string $claveAcceso;
    private DateTimeImmutable $fechaEmision;
    private string $estado;

    public function __construct(
        int $idVenta,
        string $numero,
        string $estado,
        ?string $claveAcceso = null,
        ?DateTimeImmutable $fechaEmision = null
    ) {
        if (!in_array($estado, ['emitida', 'anulada', 'pendiente_sri'], true)) {
            throw new InvalidArgumentException("Estado de factura inválido: {$estado}.");
        }

        $this->id = null;
        $this->idVenta = $idVenta;
        $this->numero = $numero;
        $this->claveAcceso = $claveAcceso;
        $this->fechaEmision = $fechaEmision ?? new DateTimeImmutable();
        $this->estado = $estado;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
    public function getIdVenta(): int { return $this->idVenta; }
    public function getNumero(): string { return $this->numero; }
    public function getClaveAcceso(): ?string { return $this->claveAcceso; }
    public function getFechaEmision(): DateTimeImmutable { return $this->fechaEmision; }
    public function getEstado(): string { return $this->estado; }

    public function setIdVenta(int $idVenta): void { $this->idVenta = $idVenta; }
    public function setNumero(string $numero): void { $this->numero = $numero; }
    public function setClaveAcceso(?string $claveAcceso): void { $this->claveAcceso = $claveAcceso; }
    public function setEstado(string $estado): void
    {
        if (!in_array($estado, ['emitida', 'anulada', 'pendiente_sri'], true)) {
            throw new InvalidArgumentException("Estado de factura inválido: {$estado}.");
        }
        $this->estado = $estado;
    }
}
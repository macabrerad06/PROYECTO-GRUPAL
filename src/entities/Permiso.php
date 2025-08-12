<?php

declare(strict_types=1);

class Permiso
{
    private ?int $id;
    private string $codigo;

    public function __construct(string $codigo)
    {
        $this->id = null;
        $this->codigo = $codigo;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
    public function getCodigo(): string { return $this->codigo; }

    public function setCodigo(string $codigo): void { $this->codigo = $codigo; }
}
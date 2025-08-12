<?php

declare(strict_types=1);

namespace App\entities;

class Rol
{
    private ?int $id;
    private string $nombre;

    public function __construct(string $nombre)
    {
        $this->id = null;
        $this->nombre = $nombre;
    }

    

    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }

    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
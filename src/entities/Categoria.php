<?php
declare(strict_types=1);

namespace App\entities;

class Categoria
{
    protected ?int $id; 
    private string $nombre;
    private ?string $descripcion; 
    private bool $estado; 
    private ?int $idPadre; 

    public function __construct(
        ?int $id = null, // Acepta ?int
        string $nombre,
        ?string $descripcion = null,
        bool $estado = true,
        ?int $idPadre = null
    ) {
        $this->id = null; 
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->idPadre = $idPadre;
    }

   
    // Getters
    public function getId(): ?int // Retorna ?int
    {
        return $this->id;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getDescripcion(): ?string // Retorna ?string
    {
        return $this->descripcion;
    }
    public function getEstado(): bool // Retorna bool
    {
        return $this->estado;
    }
    public function getIdPadre(): ?int // Retorna ?int
    {
        return $this->idPadre;
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
    public function setDescripcion(?string $descripcion): void // Acepta ?string
    {
        $this->descripcion = $descripcion;
    }
    public function setEstado(bool $estado): void // Acepta bool
    {
        $this->estado = $estado;
    }
    public function setIdPadre(?int $idPadre): void // Acepta ?int
    {
        $this->idPadre = $idPadre;
    }
}
<?php

declare(strict_types=1);

abstract class Cliente
{
    protected ?int $id; 
    protected string $email;
    protected ?string $telefono; 
    protected string $direccion;
    protected string $tipoCliente; 

    public function __construct(string $email, ?string $telefono, string $direccion, string $tipoCliente)
    {
        $this->id = null; 
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        
        if (!in_array($tipoCliente, ['NATURAL', 'JURIDICA'], true)) {
            throw new InvalidArgumentException("Tipo de cliente inválido: {$tipoCliente}. Debe ser 'NATURAL' o 'JURIDICA'.");
        }
        $this->tipoCliente = $tipoCliente;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int 
    {
        return $this->id;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setTelefono(?string $telefono): void 
    {
        $this->telefono = $telefono;
    }

    public function getTelefono(): ?string 
    {
        return $this->telefono;
    }

    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function getTipoCliente(): string
    {
        return $this->tipoCliente;
    }

}
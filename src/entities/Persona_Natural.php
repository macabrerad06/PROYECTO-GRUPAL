<?php
declare(strict_types=1);   

class Persona_Natural extends Cliente
{
    private string $nombre;
    private string $apellido;
    private string $cedula;

    public function __construct(string $email, ?string $telefono, string $direccion, string $tipoCliente, string $nombre, string $apellido, string $cedula)
    {
        if ($tipoCliente !== 'NATURAL') {
            throw new InvalidArgumentException("Para Persona_Natural, tipoCliente debe ser 'NATURAL'. Se recibió: {$tipoCliente}");
        }

        parent::__construct($email, $telefono, $direccion, $tipoCliente);
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->cedula = $cedula;
    }

    //getters
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getApellido(): string
    {
        return $this->apellido;
    }
    public function getCedula(): string
    {
        return $this->cedula;
    }

    //setters
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }
    public function setCedula(string $cedula): void
    {
        $this->cedula = $cedula;
    }



}

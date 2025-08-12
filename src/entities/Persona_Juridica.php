<?php

declare(strict_types=1);

namespace App\entities;

class Persona_Juridica extends Cliente
{
    private string $razonSocial;
    private string $ruc;
    private string $representanteLegal; 

    public function __construct(
        string $email,
        ?string $telefono,
        string $direccion,
        string $tipoCliente,
        string $razonSocial,
        string $ruc,
        string $representanteLegal 
    ) {
        if ($tipoCliente !== 'JURIDICA') {
            throw new InvalidArgumentException("Para Persona_Juridica, tipoCliente debe ser 'JURIDICA'. Se recibió: {$tipoCliente}");
        }

        parent::__construct($email, $telefono, $direccion, $tipoCliente);
        $this->razonSocial = $razonSocial;
        $this->ruc = $ruc;
        $this->representanteLegal = $representanteLegal; 
    }

    //getters
    public function getRazonSocial(): string
    {
        return $this->razonSocial;
    }
    
    public function getRuc(): string
    {
        return $this->ruc;
    }

    public function getRepresentanteLegal(): string 
    {
        return $this->representanteLegal;
    }

    //setters
    public function setRazonSocial(string $razonSocial): void
    {
        $this->razonSocial = $razonSocial;
    }
    
    public function setRuc(string $ruc): void
    {
        $this->ruc = $ruc;
    }

    public function setRepresentanteLegal(string $representanteLegal): void 
    {
        $this->representanteLegal = $representanteLegal;
    }
}
<?php

declare(strict_types=1);

class Usuario
{
    private ?int $id;
    private string $username;
    private string $passwordHash;
    private bool $estado;

    public function __construct(
        string $username,
        string $passwordHash,
        bool $estado = true
    ) {
        $this->id = null;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->estado = $estado;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getPasswordHash(): string { return $this->passwordHash; }
    public function getEstado(): bool { return $this->estado; }

    public function setUsername(string $username): void { $this->username = $username; }
    public function setPasswordHash(string $passwordHash): void { $this->passwordHash = $passwordHash; }
    public function setEstado(bool $estado): void { $this->estado = $estado; }
}
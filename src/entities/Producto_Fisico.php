<?php

declare(strict_types=1);

class ProductoFisico extends Producto
{
    private ?float $peso;
    private ?float $alto;
    private ?float $ancho;
    private ?float $profundidad;

    public function __construct(
        string $nombre,
        ?string $descripcion,
        float $precioUnitario,
        int $stock,
        int $idCategoria,
        ?float $peso = null,
        ?float $alto = null,
        ?float $ancho = null,
        ?float $profundidad = null
    ) {
        if ($peso !== null && $peso < 0) {
            throw new InvalidArgumentException("El peso no puede ser negativo.");
        }
        if ($alto !== null && $alto < 0) {
            throw new InvalidArgumentException("El alto no puede ser negativo.");
        }
        if ($ancho !== null && $ancho < 0) {
            throw new InvalidArgumentException("El ancho no puede ser negativo.");
        }
        if ($profundidad !== null && $profundidad < 0) {
            throw new InvalidArgumentException("La profundidad no puede ser negativa.");
        }

        parent::__construct($nombre, $descripcion, $precioUnitario, $stock, $idCategoria, 'FISICO');
        $this->peso = $peso;
        $this->alto = $alto;
        $this->ancho = $ancho;
        $this->profundidad = $profundidad;
    }

    public function getPeso(): ?float { return $this->peso; }
    public function getAlto(): ?float { return $this->alto; }
    public function getAncho(): ?float { return $this->ancho; }
    public function getProfundidad(): ?float { return $this->profundidad; }

    public function setPeso(?float $peso): void
    {
        if ($peso !== null && $peso < 0) {
            throw new InvalidArgumentException("El peso no puede ser negativo.");
        }
        $this->peso = $peso;
    }

    public function setAlto(?float $alto): void
    {
        if ($alto !== null && $alto < 0) {
            throw new InvalidArgumentException("El alto no puede ser negativo.");
        }
        $this->alto = $alto;
    }

    public function setAncho(?float $ancho): void
    {
        if ($ancho !== null && $ancho < 0) {
            throw new InvalidArgumentException("El ancho no puede ser negativo.");
        }
        $this->ancho = $ancho;
    }

    public function setProfundidad(?float $profundidad): void
    {
        if ($profundidad !== null && $profundidad < 0) {
            throw new InvalidArgumentException("La profundidad no puede ser negativa.");
        }
        $this->profundidad = $profundidad;
    }
}
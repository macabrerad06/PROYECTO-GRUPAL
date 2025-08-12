<?php

declare(strict_types=1);

class ProductoDigital extends Producto
{
    private string $urlDescarga;
    private string $licencia;

    public function __construct(
        string $nombre,
        ?string $descripcion,
        float $precioUnitario,
        int $stock,
        int $idCategoria,
        string $urlDescarga,
        string $licencia
    ) {
        parent::__construct($nombre, $descripcion, $precioUnitario, $stock, $idCategoria, 'DIGITAL');
        $this->urlDescarga = $urlDescarga;
        $this->licencia = $licencia;
    }

    public function getUrlDescarga(): string { return $this->urlDescarga; }
    public function getLicencia(): string { return $this->licencia; }

    public function setUrlDescarga(string $urlDescarga): void { $this->urlDescarga = $urlDescarga; }
    public function setLicencia(string $licencia): void { $this->licencia = $licencia; }
}
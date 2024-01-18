<?php

declare(strict_types=1);

namespace APP\Entity;

use APP\Repository\StockRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

use DateTime;

#[Entity(repositoryClass: StockRepository::class)]
#[Table(name: 'stock')]
class StockEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'idStock', type: 'integer')]
    private int $idStock;

    #[Column(name: 'fecha', type: Types::DATETIME_MUTABLE)]
    private DateTime $fecha;

    #[ManyToOne(targetEntity: ProductosEntity::class, inversedBy: 'stock')]
    #[JoinColumn(name: 'id_producto', referencedColumnName: 'idProducto')]
    private ProductosEntity $productoId;

    #[Column(name: 'cantidad', type: 'decimal', precision: 8, scale: 2, options: ['default' => 0.00])]
    private float $cantidad = 0.00;

    public function __construct()
    {
    }

    /**
     * Get the value of idStock
     */
    public function getIdStock(): int
    {
        return $this->idStock;
    }

    /**
     * Get the value of productoId
     */
    public function getProductoId(): ?ProductosEntity
    {

        return $this->productoId;
    }

    /**
     * Set the value of productoId
     *
     * @return  self
     */
    public function setProductoId(ProductosEntity $productoId): void
    {
        $this->productoId = $productoId;
    }

    /**
     * Get the value of fecha
     */
    public function getFecha(): DateTime
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */
    public function setFecha(DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * Get the value of cantidad
     */
    public function getCantidad(): float
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */
    public function setCantidad(float $cantidad): void
    {
        $this->cantidad = $cantidad;
    }
}

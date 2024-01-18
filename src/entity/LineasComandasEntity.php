<?php

declare(strict_types=1);

namespace APP\Entity;

use APP\Repository\LineasComandasRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

#[Entity(repositoryClass: LineasComandasRepository::class)]
#[Table(name: 'lineascomandas')]
class LineasComandasEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'idlinea', type: Types::INTEGER)]
    private int $idlinea;

    #[ManyToOne(targetEntity: ComandasEntity::class, inversedBy: 'lineasComandas')]
    #[JoinColumn(name: 'idComanda', referencedColumnName: 'idComanda')]
    private ComandasEntity $comandaId;

    #[ManyToOne(targetEntity: ProductosEntity::class, inversedBy: 'lineasComandas')]
    #[JoinColumn(name: 'idProducto', referencedColumnName: 'idProducto')]
    private ProductosEntity $productoId;

    #[Column(name: 'cantidad', type: 'decimal', precision: 8, scale: 2)]
    private float $cantidad;

    #[Column(name: 'entregado', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $entregado = false;

    public function __construct()
    {
    
    }

    /**
     * Get the value of idlinea
     */
    public function getIdlinea(): int
    {
        return $this->idlinea;
    }

    /**
     * Get the value of comandaId
     */
    public function getComandaId(): ComandasEntity
    {
        return $this->comandaId;
    }

    /**
     * Set the value of comandaId
     *
     * @return  self
     */
    public function setComandaId(ComandasEntity $comandaId): void
    {
        $this->comandaId = $comandaId;
    }

    /**
     * Get the value of productoId
     */
    public function getProductoId(): ProductosEntity
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

    /**
     * Get the value of entregado
     */
    public function getEntregado(): bool
    {
        return $this->entregado;
    }

    /**
     * Set the value of entregado
     *
     * @return  self
     */
    public function setEntregado(bool $entregado): void
    {
        $this->entregado = $entregado;
    }
}

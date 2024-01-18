<?php

declare(strict_types=1);

namespace APP\Entity;

use APP\Repository\LineasPedidosRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

#[Entity(repositoryClass: LineasPedidosRepository::class)]
#[Table(name: 'lineaspedidos')]
class LineasPedidosEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'idLinea', type: Types::INTEGER)]
    private int $idLinea;

    #[ManyToOne(targetEntity: PedidosEntity::class, inversedBy: 'lineasPedidos')]
    #[JoinColumn(name: 'idPedido', referencedColumnName: 'idPedidos')]
    private PedidosEntity $pedidosId;

    #[ManyToOne(targetEntity: ProductosEntity::class, inversedBy: 'lineasPedidos')]
    #[JoinColumn(name: 'idProducto', referencedColumnName: 'idProducto')]
    private ProductosEntity $productoId;

    #[Column(name: 'cantidad', type: 'decimal', precision: 8, scale: 2)]
    private float $cantidad;

    #[Column(name:'entregado', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $entregado = false;

    public function __construct()
    {
    }

    /**
     * Get the value of idLinea
     */
    public function getIdLinea(): int
    {
        return $this->idLinea;
    }

    /**
     * Get the value of pedidosId
     */
    public function getPedidosId(): PedidosEntity
    {
        return $this->pedidosId;
    }

    /**
     * Set the value of pedidosId
     *
     * @return  self
     */
    public function setPedidosId(PedidosEntity $pedidosId): void
    {
        $this->pedidosId = $pedidosId;
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

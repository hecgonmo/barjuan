<?php

declare(strict_types=1);

namespace APP\Entity;

use APP\Repository\ProductosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\GeneratedValue;

#[Entity(repositoryClass: ProductosRepository::class)]
#[Table(name: 'productos')]
class ProductosEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'idProducto', type: 'integer')]
    private int $idProducto;

    #[Column(name: 'nombre', type: Types::STRING, length: 50, unique: true)]
    private string $nombre;

    #[Column(name: 'descripcion', type: Types::STRING, length: 100, nullable: true, options: ['default' => null])]
    private ?string $descripcion;

    #[Column(name: 'precio', type: 'decimal', precision: 8, scale: 2, options: ['default' => 0.00])]
    private float $precio;

    #[OneToMany(targetEntity: StockEntity::class, mappedBy: 'productoId')]
    private Collection $stock;

    #[OneToMany(targetEntity: LineasPedidosEntity::class, mappedBy: 'productoId')]
    private Collection $lineasPedidos;

    #[OneToMany(targetEntity: LineasComandasEntity::class, mappedBy: 'productoId')]
    private Collection $lineasComandas;

    public function __construct(int $idProducto)
    {
        $this->idProducto = $idProducto;
        $this->stock = new ArrayCollection();
        $this->lineasPedidos = new ArrayCollection();
        $this->lineasComandas = new ArrayCollection();
    }


    /**
     * Get the value of idProducto
     */
    public function getIdProducto(): int
    {
        return $this->idProducto;
    }




    /**
     * Get the value of nombre
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */
    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Get the value of precio
     */
    public function getPrecio(): float
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */
    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    /**
     * Get the value of stock
     */
    public function getStock(): Collection
    {
        return $this->stock;
    }

    /**
     * Set the value of stock
     *
     * @return  self
     */
    public function setStock(Collection $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * Get the value of lineasPedidos
     */
    public function getLineasPedidos(): Collection
    {
        return $this->lineasPedidos;
    }

    /**
     * Set the value of lineasPedidos
     *
     * @return  self
     */
    public function setLineasPedidos(Collection $lineasPedidos): void
    {
        $this->lineasPedidos = $lineasPedidos;
    }

    /**
     * Get the value of lineasComandas
     */
    public function getLineasComandas(): Collection
    {
        return $this->lineasComandas;
    }

    /**
     * Set the value of lineasComandas
     *
     * @return  self
     */
    public function setLineasComandas(Collection $lineasComandas): void
    {
        $this->lineasComandas = $lineasComandas;
    }
}

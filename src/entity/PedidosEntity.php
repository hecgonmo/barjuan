<?php

declare(strict_types=1);

namespace APP\Entity;

use APP\Repository\PedidosRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;

#[Entity(repositoryClass: PedidosRepository::class)]
#[Table(name: 'pedidos')]
class PedidosEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'idPedidos', type: Types::INTEGER)]
    private int $idPedidos;

    #[Column(name: 'fecha', type: Types::DATETIME_MUTABLE,)]
    private DateTime $fecha;

    #[ManyToOne(targetEntity: ProveedoresEntity::class, inversedBy: 'idProveedor')]
    #[JoinColumn(name: 'idProveedor', referencedColumnName: 'idProveedor')]
    private ProveedoresEntity $proveedorId;

    #[Column(name: 'detalles', type: TYPES::STRING, length: 100, nullable: true, options: ['default' => null])]
    private ?string $detalles;

    #[Column(name: 'estado', type: Types::BOOLEAN, options: ['default' => true])]
    private bool $estado = true;

    #[OneToMany(targetEntity: LineasPedidosEntity::class, mappedBy: 'pedidosId')]
    private Collection $lineasPedidos;

    public function __construct()
    {
        $this->lineasPedidos = new ArrayCollection();
    }


    /**
     * Get the value of idPedidos
     */
    public function getIdPedidos(): int
    {
        return $this->idPedidos;
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
     * Get the value of proveedorId
     */
    public function getProveedorId(): ProveedoresEntity
    {
        return $this->proveedorId;
    }

    /**
     * Set the value of proveedorId
     *
     * @return  self
     */
    public function setProveedorId(ProveedoresEntity $proveedorId): void
    {
        $this->proveedorId = $proveedorId;
    }

    /**
     * Get the value of detalles
     */
    public function getDetalles(): ?string
    {
        return $this->detalles;
    }

    /**
     * Set the value of detalles
     *
     * @return  self
     */
    public function setDetalles(?string $detalles): void
    {
        $this->detalles = $detalles;
    }

    /**
     * Get the value of estado
     */
    public function getEstado(): bool
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */
    public function setEstado(bool $estado): void
    {
        $this->estado = $estado;
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
}

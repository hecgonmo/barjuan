<?php

declare(strict_types=1);

namespace APP\Entity;

use APP\Repository\ComandasRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

#[Entity(repositoryClass: ComandasRepository::class)]
#[Table(name: 'comandas')]
class ComandasEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'idComanda', type: Types::INTEGER)]
    private int $idComanda;

    #[Column(name: 'fecha', type: Types::DATETIME_MUTABLE,)]
    private DateTime $fecha;

    #[ManyToOne(targetEntity: MesaEntity::class, inversedBy: 'comandas')]
    #[JoinColumn(name: 'idMesa', referencedColumnName: 'idMesa')]
    private MesaEntity $mesaId;

    #[Column(name: 'comensales', type: Types::INTEGER, length: 11)]
    private int $comensales;

    #[Column(name: 'detalles', type: TYPES::STRING, length: 255, nullable: true, options: ['default' => null])]
    private ?string $detalles = null;

    #[Column(name: 'estado', type: Types::BOOLEAN, options: ['default' => true])]
    private bool $estado = true;

    #[OneToMany(targetEntity: LineasComandasEntity::class, mappedBy: 'comandaId')]
    private Collection $lineasComandas;

    #[OneToMany(targetEntity: TicketsEntity::class, mappedBy: 'comandaId')]
    private Collection $tickets;

    public function __construct()
    {
        $this->lineasComandas = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    /**
     * Get the value of idComanda
     */
    public function getIdComanda(): int
    {
        return $this->idComanda;
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
    public function setFecha(DateTime $fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Get the value of mesaId
     */
    public function getMesaId(): MesaEntity
    {
        return $this->mesaId;
    }

    /**
     * Set the value of mesaId
     *
     * @return  self
     */
    public function setMesaId(MesaEntity $mesaId): void
    {
        $this->mesaId = $mesaId;
    }

    /**
     * Get the value of comensales
     */
    public function getComensales(): int
    {
        return $this->comensales;
    }

    /**
     * Set the value of comensales
     *
     * @return  self
     */
    public function setComensales(int $comensales): void
    {
        $this->comensales = $comensales;
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
    public function setDetalles(?string $detalles)
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

    /**
     * Get the value of tickets
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    /**
     * Set the value of tickets
     *
     * @return  self
     */
    public function setTickets(Collection $tickets): void
    {
        $this->tickets = $tickets;
    }
}

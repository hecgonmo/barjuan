<?php

declare(strict_types=1);

namespace APP\Entity;

use APP\Repository\TicketsRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use DateTime;


#[Entity(repositoryClass: TicketsRepository::class)]
#[Table(name: 'tickets')]
class TicketsEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'idTicket', type: Types::INTEGER)]
    private int $idTicket;

    #[Column(name: 'fecha', type: Types::DATETIME_MUTABLE,)]
    private DateTime $fecha;

    #[ManyToOne(targetEntity: ComandasEntity::class, inversedBy: 'tickets')]
    #[JoinColumn(name: 'idComanda', referencedColumnName: 'idComanda')]
    private ComandasEntity $comandaId;

    #[Column(name: 'importe', type: 'decimal', precision: 8, scale: 2)]
    private float $importe;

    #[Column(name: 'pagado', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $pagado = false;

    public function __construct()
    {
    }

    /**
     * Get the value of idTicket
     */
    public function getIdTicket(): int
    {
        return $this->idTicket;
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
     * Get the value of importe
     */
    public function getImporte(): float
    {
        return $this->importe;
    }

    /**
     * Set the value of importe
     *
     * @return  self
     */
    public function setImporte(float $importe): void
    {
        $this->importe = $importe;
    }

    /**
     * Get the value of pagado
     */
    public function getPagado(): bool
    {
        return $this->pagado;
    }

    /**
     * Set the value of pagado
     *
     * @return  self
     */
    public function setPagado(bool $pagado): void
    {
        $this->pagado = $pagado;
    }
}

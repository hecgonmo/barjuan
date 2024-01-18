<?php

declare(strict_types=1);

namespace APP\Entity;

use APP\Repository\MesaRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[Entity(repositoryClass: MesaRepository::class)]
#[Table(name: 'mesa')]
class MesaEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'idMesa', type: Types::INTEGER)]
    private int $idMesa;

    #[Column(name: 'nombre', type: Types::STRING, length: 50, unique: true)]
    private string $nombre;

    #[Column(name: 'comensales', type: Types::INTEGER, length: 11)]
    private int $comensales;

    #[OneToMany(targetEntity: ComandasEntity::class, mappedBy: 'mesaId')]
    private Collection $comandas;

    public function __construct(int $idMesa)
    {
        $this->idMesa = $idMesa;
        $this->comandas = new ArrayCollection();
    }

    /**
     * Get the value of idMesa
     */
    public function getIdMesa(): int
    {
        return $this->idMesa;
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
     * Get the value of comandas
     */
    public function getComandas(): Collection
    {
        return $this->comandas;
    }

    /**
     * Set the value of comandas
     *
     * @return  self
     */
    public function setComandas(Collection $comandas): void
    {
        $this->comandas = $comandas;
    }
}

<?php

declare(strict_types=1);

namespace APP\Entity;

use APP\Repository\ProveedoresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\GeneratedValue;


#[Entity(repositoryClass: ProveedoresRepository::class)]
#[Table(name: 'proveedores')]
class ProveedoresEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'idProveedor', type: Types::INTEGER)]
    private int $idProveedor;

    #[Column(name: 'nombre', type: Types::STRING, length: 255, unique: true)]
    private string $nombre;

    #[Column(name: 'cif', type: Types::STRING, length: 9, unique: true)]
    private string $cif;

    #[Column(name: 'direccion', type: Types::STRING, length: 65535)]
    private string $direccion;

    #[Column(name: 'telefono', type: Types::INTEGER, length: 11, nullable: true, options: ['default' => null])]
    private ?int $telefono = null;

    #[Column(name: 'email', type: Types::STRING, length: 100, nullable: true, options: ['default' => null])]
    private ?string $email = null;

    #[Column(name: 'contacto', type: Types::STRING, length: 100, nullable: true, options: ['default' => null])]
    private ?string $contacto = null;

    #[OneToMany(targetEntity: PedidosEntity::class, mappedBy: 'proveedorId')]
    private Collection $pedidos;

    public function __construct()
    {
        $this->pedidos = new ArrayCollection();
    }

    /**
     * Get the value of idProveedor
     */
    public function getIdProveedor(): int
    {
        return $this->idProveedor;
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
     * Get the value of cif
     */
    public function getCif(): string
    {
        return $this->cif;
    }

    /**
     * Set the value of cif
     *
     * @return  self
     */
    public function setCif(string $cif): void
    {
        $this->cif = $cif;
    }

    /**
     * Get the value of direccion
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     * Set the value of direccion
     *
     * @return  self
     */
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * Get the value of telefono
     */
    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */
    public function setTelefono(?int $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Get the value of contacto
     */
    public function getContacto(): ?string
    {
        return $this->contacto;
    }

    /**
     * Set the value of contacto
     *
     * @return  self
     */
    public function setContacto(?string $contacto): void
    {
        $this->contacto = $contacto;
    }

    /**
     * Get the value of pedidos
     */
    public function getPedidos(): Collection
    {
        return $this->pedidos;
    }

    /**
     * Set the value of pedidos
     *
     * @return  self
     */
    public function setPedidos(Collection $pedidos): void
    {
        $this->pedidos = $pedidos;
    }
}

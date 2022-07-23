<?php

namespace App\Entity;

use App\Repository\TransaccionesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransaccionesRepository::class)]
class Transacciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'transacciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cuentas $cuenta_destino = null;

    #[ORM\ManyToOne(inversedBy: 'cuenta_origen')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cuentas $cuenta_origen = null;

    #[ORM\Column]
    private ?float $monto = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comentarios = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $tipo = null;

    #[ORM\ManyToOne(inversedBy: 'transacciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCuentaDestino(): ?Cuentas
    {
        return $this->cuenta_destino;
    }

    public function setCuentaDestino(?Cuentas $cuenta_destino): self
    {
        $this->cuenta_destino = $cuenta_destino;

        return $this;
    }

    public function getCuentaOrigen(): ?Cuentas
    {
        return $this->cuenta_origen;
    }

    public function setCuentaOrigen(?Cuentas $cuenta_origen): self
    {
        $this->cuenta_origen = $cuenta_origen;

        return $this;
    }

    public function getMonto(): ?float
    {
        return $this->monto;
    }

    public function setMonto(float $monto): self
    {
        $this->monto = $monto;

        return $this;
    }

    public function getComentarios(): ?string
    {
        return $this->comentarios;
    }

    public function setComentarios(?string $comentarios): self
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(int $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

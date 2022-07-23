<?php

namespace App\Entity;

use App\Repository\TercerosRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TercerosRepository::class)]
class Terceros
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'registros_terceros')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usr_registro = null;

    #[ORM\ManyToOne(inversedBy: 'registro_cuenta')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cuentas $id_cuenta = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $update_at = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $num_cuenta = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsrRegistro(): ?User
    {
        return $this->usr_registro;
    }

    public function setUsrRegistro(?User $usr_registro): self
    {
        $this->usr_registro = $usr_registro;

        return $this;
    }

    public function getIdCuenta(): ?Cuentas
    {
        return $this->id_cuenta;
    }

    public function setIdCuenta(?Cuentas $id_cuenta): self
    {
        $this->id_cuenta = $id_cuenta;

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

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->update_at;
    }

    public function setUpdateAt(\DateTimeImmutable $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function getNumCuenta(): ?string
    {
        return $this->num_cuenta;
    }

    public function setNumCuenta(string $num_cuenta): self
    {
        $this->num_cuenta = $num_cuenta;

        return $this;
    }
}

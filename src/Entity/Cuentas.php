<?php

namespace App\Entity;

use App\Repository\CuentasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CuentasRepository::class)]
class Cuentas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nickname = null;

    #[ORM\Column(nullable: true)]
    private ?float $num_cuenta = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estado = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $update_at = null;

    #[ORM\ManyToOne(inversedBy: 'cuentas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'cuenta_destino', targetEntity: Transacciones::class, orphanRemoval: true)]
    private Collection $transacciones;

    #[ORM\OneToMany(mappedBy: 'cuenta_origen', targetEntity: Transacciones::class, orphanRemoval: true)]
    private Collection $cuenta_origen;

    #[ORM\OneToMany(mappedBy: 'id_cuenta', targetEntity: Terceros::class, orphanRemoval: true)]
    private Collection $registro_cuenta;

    #[ORM\Column(nullable: true)]
    private ?float $saldo = null;

    public function __construct($userId=null,$num_cuenta=null,$nickname=null,$saldo = null)
    {
        $fecha = new \DateTimeImmutable();
        $this->user = $userId;
        $this->nickname = $nickname;
        $this->num_cuenta = $num_cuenta;
        $this->estado = 1;
        $this->saldo = $saldo;
        $this->created_at = $fecha;
        $this->update_at = $fecha;
        $this->transacciones = new ArrayCollection();
        $this->cuenta_origen = new ArrayCollection();
        $this->registro_cuenta = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getNumCuenta(): ?float
    {
        return $this->num_cuenta;
    }

    public function setNumCuenta(?float $num_cuenta): self
    {
        $this->num_cuenta = $num_cuenta;

        return $this;
    }

    public function isEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(?bool $estado): self
    {
        $this->estado = $estado;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Transacciones>
     */
    public function getTransacciones(): Collection
    {
        return $this->transacciones;
    }

    public function addTransaccione(Transacciones $transaccione): self
    {
        if (!$this->transacciones->contains($transaccione)) {
            $this->transacciones[] = $transaccione;
            $transaccione->setCuentaDestino($this);
        }

        return $this;
    }

    public function removeTransaccione(Transacciones $transaccione): self
    {
        if ($this->transacciones->removeElement($transaccione)) {
            // set the owning side to null (unless already changed)
            if ($transaccione->getCuentaDestino() === $this) {
                $transaccione->setCuentaDestino(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transacciones>
     */
    public function getCuentaOrigen(): Collection
    {
        return $this->cuenta_origen;
    }

    public function addCuentaOrigen(Transacciones $cuentaOrigen): self
    {
        if (!$this->cuenta_origen->contains($cuentaOrigen)) {
            $this->cuenta_origen[] = $cuentaOrigen;
            $cuentaOrigen->setCuentaOrigen($this);
        }

        return $this;
    }

    public function removeCuentaOrigen(Transacciones $cuentaOrigen): self
    {
        if ($this->cuenta_origen->removeElement($cuentaOrigen)) {
            // set the owning side to null (unless already changed)
            if ($cuentaOrigen->getCuentaOrigen() === $this) {
                $cuentaOrigen->setCuentaOrigen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Terceros>
     */
    public function getRegistroCuenta(): Collection
    {
        return $this->registro_cuenta;
    }

    public function addRegistroCuentum(Terceros $registroCuentum): self
    {
        if (!$this->registro_cuenta->contains($registroCuentum)) {
            $this->registro_cuenta[] = $registroCuentum;
            $registroCuentum->setIdCuenta($this);
        }

        return $this;
    }

    public function removeRegistroCuentum(Terceros $registroCuentum): self
    {
        if ($this->registro_cuenta->removeElement($registroCuentum)) {
            // set the owning side to null (unless already changed)
            if ($registroCuentum->getIdCuenta() === $this) {
                $registroCuentum->setIdCuenta(null);
            }
        }

        return $this;
    }

    public function getSaldo(): ?float
    {
        return $this->saldo;
    }

    public function setSaldo(?float $saldo): self
    {
        $this->saldo = $saldo;

        return $this;
    }
}

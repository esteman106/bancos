<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 15, unique: true)]
    private ?string $identificacion = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Cuentas::class, orphanRemoval: true)]
    private Collection $cuentas;

    #[ORM\OneToMany(mappedBy: 'usr_registro', targetEntity: Terceros::class, orphanRemoval: true)]
    private Collection $registros_terceros;

    #[ORM\Column(length: 255)]
    private ?string $nombres = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Transacciones::class, orphanRemoval: true)]
    private Collection $transacciones;

    public function __construct($id = null,$email = null,$identificacion = null, $password = null,$nombres = null)
    {
        $this->id =$id;
        $this->email = $email;
        $this->identificacion = $identificacion;
        $this->identificacion = $password;
        $this->nombres = $nombres;
        $this->cuentas = new ArrayCollection();
        $this->registros_terceros = new ArrayCollection();
        $this->transacciones = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->identificacion;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getIdentificacion(): ?int
    {
        return $this->identificacion;
    }

    public function setIdentificacion(int $identificacion): self
    {
        $this->identificacion = $identificacion;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Cuentas>
     */
    public function getCuentas(): Collection
    {
        return $this->cuentas;
    }

    public function addCuenta(Cuentas $cuenta): self
    {
        if (!$this->cuentas->contains($cuenta)) {
            $this->cuentas[] = $cuenta;
            $cuenta->setUser($this);
        }

        return $this;
    }

    public function removeCuenta(Cuentas $cuenta): self
    {
        if ($this->cuentas->removeElement($cuenta)) {
            // set the owning side to null (unless already changed)
            if ($cuenta->getUser() === $this) {
                $cuenta->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Terceros>
     */
    public function getRegistrosTerceros(): Collection
    {
        return $this->registros_terceros;
    }

    public function addRegistrosTercero(Terceros $registrosTercero): self
    {
        if (!$this->registros_terceros->contains($registrosTercero)) {
            $this->registros_terceros[] = $registrosTercero;
            $registrosTercero->setUsrRegistro($this);
        }

        return $this;
    }

    public function removeRegistrosTercero(Terceros $registrosTercero): self
    {
        if ($this->registros_terceros->removeElement($registrosTercero)) {
            // set the owning side to null (unless already changed)
            if ($registrosTercero->getUsrRegistro() === $this) {
                $registrosTercero->setUsrRegistro(null);
            }
        }

        return $this;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

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
            $transaccione->setUser($this);
        }

        return $this;
    }

    public function removeTransaccione(Transacciones $transaccione): self
    {
        if ($this->transacciones->removeElement($transaccione)) {
            // set the owning side to null (unless already changed)
            if ($transaccione->getUser() === $this) {
                $transaccione->setUser(null);
            }
        }

        return $this;
    }
}

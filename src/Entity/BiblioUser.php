<?php

namespace App\Entity;

use App\Repository\BiblioUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=BiblioUserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class BiblioUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $section;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCaution;

    /**
     * @ORM\OneToMany(targetEntity=BiblioEmprunt::class, mappedBy="eleve")
     */
    private $biblioEmprunts;

    /**
     * @ORM\Column(type="integer")
     */
    private $emprunts;

    public function __construct()
    {
        $this->biblioEmprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(string $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getIsCaution(): ?bool
    {
        return $this->isCaution;
    }

    public function setIsCaution(bool $isCaution): self
    {
        $this->isCaution = $isCaution;

        return $this;
    }

    /**
     * @return Collection|BiblioEmprunt[]
     */
    public function getBiblioEmprunts(): Collection
    {
        return $this->biblioEmprunts;
    }

    public function addBiblioEmprunt(BiblioEmprunt $biblioEmprunt): self
    {
        if (!$this->biblioEmprunts->contains($biblioEmprunt)) {
            $this->biblioEmprunts[] = $biblioEmprunt;
            $biblioEmprunt->setEleve($this);
        }

        return $this;
    }

    public function removeBiblioEmprunt(BiblioEmprunt $biblioEmprunt): self
    {
        if ($this->biblioEmprunts->contains($biblioEmprunt)) {
            $this->biblioEmprunts->removeElement($biblioEmprunt);
            // set the owning side to null (unless already changed)
            if ($biblioEmprunt->getEleve() === $this) {
                $biblioEmprunt->setEleve(null);
            }
        }

        return $this;
    }

    public function getEmprunts(): ?int
    {
        return $this->emprunts;
    }

    public function setEmprunts(int $emprunts): self
    {
        $this->emprunts = $emprunts;

        return $this;
    }
}

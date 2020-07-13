<?php

namespace App\Entity;

use App\Repository\BiblioBookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BiblioBookRepository::class)
 */
class BiblioBook
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $editeur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dewey;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAjout;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDispo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateIndispo;

    /**
     * @ORM\OneToMany(targetEntity=BiblioEmprunt::class, mappedBy="livre")
     */
    private $biblioEmprunts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    public function __construct()
    {
        $this->biblioEmprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getEditeur(): ?string
    {
        return $this->editeur;
    }

    public function setEditeur(?string $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getDewey(): ?int
    {
        return $this->dewey;
    }

    public function setDewey(?int $dewey): self
    {
        $this->dewey = $dewey;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getIsDispo(): ?bool
    {
        return $this->isDispo;
    }

    public function setIsDispo(bool $isDispo): self
    {
        $this->isDispo = $isDispo;

        return $this;
    }

    public function getDateIndispo(): ?\DateTimeInterface
    {
        return $this->dateIndispo;
    }

    public function setDateIndispo(?\DateTimeInterface $dateIndispo): self
    {
        $this->dateIndispo = $dateIndispo;

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
            $biblioEmprunt->setLivre($this);
        }

        return $this;
    }

    public function removeBiblioEmprunt(BiblioEmprunt $biblioEmprunt): self
    {
        if ($this->biblioEmprunts->contains($biblioEmprunt)) {
            $this->biblioEmprunts->removeElement($biblioEmprunt);
            // set the owning side to null (unless already changed)
            if ($biblioEmprunt->getLivre() === $this) {
                $biblioEmprunt->setLivre(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
}

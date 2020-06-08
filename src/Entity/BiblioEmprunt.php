<?php

namespace App\Entity;

use App\Repository\BiblioEmpruntRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BiblioEmpruntRepository::class)
 */
class BiblioEmprunt
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=BiblioUser::class, inversedBy="biblioEmprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity=BiblioBook::class, inversedBy="biblioEmprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $livre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEmprunt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEmprunt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRetour;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEleve(): ?BiblioUser
    {
        return $this->eleve;
    }

    public function setEleve(?BiblioUser $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getLivre(): ?BiblioBook
    {
        return $this->livre;
    }

    public function setLivre(?BiblioBook $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getIsEmprunt(): ?bool
    {
        return $this->isEmprunt;
    }

    public function setIsEmprunt(bool $isEmprunt): self
    {
        $this->isEmprunt = $isEmprunt;

        return $this;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(\DateTimeInterface $dateEmprunt): self
    {
        $this->dateEmprunt = $dateEmprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(?\DateTimeInterface $dateRetour): self
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }
}

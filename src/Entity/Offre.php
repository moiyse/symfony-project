<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $secteur;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @Assert\NotNull
     * @ORM\Column(type="float")
     */
    private $salaire;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    public $datee_publication;

    /**
     * @ORM\OneToMany(targetEntity=Candidature::class, mappedBy="Offre", orphanRemoval=true)
     */
    private $candidatures;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $localisation;

    /**
     * @ORM\OneToOne(targetEntity=Bibliotheque::class, mappedBy="Offre", cascade={"persist", "remove"})
     */
    private $bibliotheque;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="offres")
     */
    private $User;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();

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

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(string $secteur): self
    {
        $this->secteur = $secteur;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(float $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getDateePublication():  ?string
    {
        return $this->datee_publication;
    }

    public function setDateePublication(String $datee_publication): self
    {
        $this->datee_publication = $datee_publication;

        return $this;
    }

    /**
     * @return Collection|Candidature[]
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setOffre($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getOffre() === $this) {
                $candidature->setOffre(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->titre;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getBibliotheque(): ?Bibliotheque
    {
        return $this->bibliotheque;
    }

    public function setBibliotheque(?Bibliotheque $bibliotheque): self
    {
        // unset the owning side of the relation if necessary
        if ($bibliotheque === null && $this->bibliotheque !== null) {
            $this->bibliotheque->setOffre(null);
        }

        // set the owning side of the relation if necessary
        if ($bibliotheque !== null && $bibliotheque->getOffre() !== $this) {
            $bibliotheque->setOffre($this);
        }

        $this->bibliotheque = $bibliotheque;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }


  

}

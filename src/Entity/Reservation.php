<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Evenement;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private $nbplace;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="integer")
     */
    private $eve_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     */
    private $user;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbplace(): ?int
    {
        return $this->nbplace;
    }

    public function setNbplace(int $nbplace): self
    {
        $this->nbplace = $nbplace;

        return $this;
    }

    public function getEve_id(): ?int
    {
        return $this->eve_id;
    }

    public function setEve_id(int $eve): self
    {
        $this->eve_id = $eve;

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

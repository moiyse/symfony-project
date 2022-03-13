<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements  UserInterface, PasswordAuthenticatedUserInterface,TwoFactorInterface,\Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @Groups("post:read")
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     * @Groups("post:read")
     */
    private $etat;
    /**
     * @ORM\Column(type="json")
     * @Groups("post:read")
     */
    private $roles = [];
    /**
     * @ORM\OneToMany(targetEntity=Likes::class, mappedBy="user_id")
     */
    private $likes_id;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups("post:read")
     */
    private $password;
    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank
     * @Groups("post:read")
     */
    private $image;

    /**
     * @ORM\Column(name="googleAuthenticatorSecret", type="string", nullable=true)
     * @Groups("post:read")
     */
    private ?string $googleAuthenticatorSecret;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="users")
     */
    private $User;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="User",orphanRemoval=true)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Abonnement::class, inversedBy="users")
     */
    private $Abonnement;

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="User")
     */
    private $offres;

    /**
     * @ORM\OneToMany(targetEntity=Candidature::class, mappedBy="User")
     */
    private $candidatures;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="user")
     */
    private $evenements;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="user")
     */
    private $reservations;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function serialize() {

        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->roles,
        ));

    }

    public function unserialize($serialized) {

        list (
            $this->id,
            $this->email,
            $this->password,
            $this->roles,
            ) = unserialize($serialized);
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email ): self
    {
        $this->email = $email;

        return $this;
    }
    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat )
    {
        $this->etat = $etat;

        return $this;
    }
    public function getImage()
    {
        return $this->image;
    }

    public function setImage( $image )
    {
        $this->image = $image;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function isGoogleAuthenticatorEnabled(): bool
    {
        return null !== $this->googleAuthenticatorSecret;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->email;
    }

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }
    public function addRoles(string $roles):self{
        if(!in_array($roles,$this->$roles)){
            $this->roles[] = $roles;
        }
        return $this;
    }

    public function getUser(): ?self
    {
        return $this->User;
    }

    public function setUser(?self $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(self $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setUser($this);
        }

        return $this;
    }

    public function removeUser(self $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUser() === $this) {
                $user->setUser(null);
            }
        }

        return $this;

    }
    /**
     * @return Collection|Likes[]
     */
    public function getLikesId(): Collection
    {
        return $this->likes_id;
    }

    public function addLikesId(Likes $likesId): self
    {
        if (!$this->likes_id->contains($likesId)) {
            $this->likes_id[] = $likesId;
            $likesId->setUserId($this);
        }

        return $this;
    }

    public function removeLikesId(Likes $likesId): self
    {
        if ($this->likes_id->removeElement($likesId)) {
            // set the owning side to null (unless already changed)
            if ($likesId->getUserId() === $this) {
                $likesId->setUserId(null);
            }
        }

        return $this;
    }

    public function getAbonnement(): ?Abonnement
    {
        return $this->Abonnement;
    }

    public function setAbonnement(?Abonnement $Abonnement): self
    {
        $this->Abonnement = $Abonnement;

        return $this;
    }

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setUser($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getUser() === $this) {
                $offre->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setUser($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getUser() === $this) {
                $candidature->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setUser($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getUser() === $this) {
                $evenement->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

        return $this;
    }

  
}

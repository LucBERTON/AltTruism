<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $signedupOn = null;

    #[ORM\ManyToMany(targetEntity: Quest::class, inversedBy: 'participants')]
    private Collection $acceptedQuests;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Quest::class)]
    private Collection $createdQuests;

    public function __construct()
    {
        $this->acceptedQuests = new ArrayCollection();
        $this->createdQuests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
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

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSignedupOn(): ?\DateTimeInterface
    {
        return $this->signedupOn;
    }

    public function setSignedupOn(\DateTimeInterface $signedupOn): self
    {
        $this->signedupOn = $signedupOn;

        return $this;
    }

    /**
     * @return Collection<int, Quest>
     */
    public function getAcceptedQuests(): Collection
    {
        return $this->acceptedQuests;
    }

    public function addAcceptedQuest(Quest $acceptedQuest): self
    {
        if (!$this->acceptedQuests->contains($acceptedQuest)) {
            $this->acceptedQuests->add($acceptedQuest);
        }

        return $this;
    }

    public function removeAcceptedQuest(Quest $acceptedQuest): self
    {
        $this->acceptedQuests->removeElement($acceptedQuest);

        return $this;
    }

    /**
     * @return Collection<int, Quest>
     */
    public function getCreatedQuests(): Collection
    {
        return $this->createdQuests;
    }

    public function addCreatedQuest(Quest $createdQuest): self
    {
        if (!$this->createdQuests->contains($createdQuest)) {
            $this->createdQuests->add($createdQuest);
            $createdQuest->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedQuest(Quest $createdQuest): self
    {
        if ($this->createdQuests->removeElement($createdQuest)) {
            // set the owning side to null (unless already changed)
            if ($createdQuest->getCreatedBy() === $this) {
                $createdQuest->setCreatedBy(null);
            }
        }

        return $this;
    }
}

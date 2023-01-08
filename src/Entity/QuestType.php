<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuestTypeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: QuestTypeRepository::class)]
#[ApiResource]
class QuestType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'questType', targetEntity: Quest::class)]
    private Collection $questsOfThisType;

    public function __construct()
    {
        $this->questsOfThisType = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, Quest>
     */
    public function getQuestsOfThisType(): Collection
    {
        return $this->questsOfThisType;
    }

    public function addQuestsOfThisType(Quest $questsOfThisType): self
    {
        if (!$this->questsOfThisType->contains($questsOfThisType)) {
            $this->questsOfThisType->add($questsOfThisType);
            $questsOfThisType->setQuestType($this);
        }

        return $this;
    }

    public function removeQuestsOfThisType(Quest $questsOfThisType): self
    {
        if ($this->questsOfThisType->removeElement($questsOfThisType)) {
            // set the owning side to null (unless already changed)
            if ($questsOfThisType->getQuestType() === $this) {
                $questsOfThisType->setQuestType(null);
            }
        }

        return $this;
    }
}

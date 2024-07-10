<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    #[ORM\Column]
    private ?int $timestamp = null;

    #[ORM\ManyToOne(fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    // #[Assert\Choice(['New York', 'Berlin', 'Tokyo'])]
    private ?EventCategory $eventCategory = null;

    #[ORM\Column(length: 2024)]
    #[Assert\NotBlank(message: 'Renseigner les détails ici')]
    #[Assert\Length(
        min: 5,
        minMessage: 'Les détails doivent contenir au moins {{ limit }} caractères',
        max: 2024,
        maxMessage: 'Les détails ne doivent pas faire plus de {{ limit }} caractères'
    )]
    private ?string $privateDetails = null;

    #[ORM\Column(length: 2024, nullable:true)]
    #[Assert\Length(
        max: 2024,
        maxMessage: 'Les détails ne doivent pas faire plus de {{ limit }} caractères'
    )]
    private ?string $publicDetails = null;

    #[ORM\Column]
    private ?bool $status = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $updateUser = null;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'Event', orphanRemoval: true)]
    private Collection $participation;

    public function __construct()
    {
        $this->participation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getEventCategory(): ?EventCategory
    {
        return $this->eventCategory;
    }

    public function setEventCategory(?EventCategory $eventCategory): static
    {
        $this->eventCategory = $eventCategory;

        return $this;
    }

    public function getPrivateDetails(): ?string
    {
        return $this->privateDetails;
    }

    public function setPrivateDetails(string $privateDetails): static
    {
        $this->privateDetails = $privateDetails;

        return $this;
    }

    public function getPublicDetails(): ?string
    {
        return $this->publicDetails;
    }

    public function setPublicDetails(string $publicDetails): static
    {
        $this->publicDetails = $publicDetails;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdateUser(): ?User
    {
        return $this->updateUser;
    }

    public function setUpdateUser(?User $updateUser): static
    {
        $this->updateUser = $updateUser;

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participation->contains($participation)) {
            $this->participation->add($participation);
            $participation->setEvent($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participation->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getEvent() === $this) {
                $participation->setEvent(null);
            }
        }

        return $this;
    }
}

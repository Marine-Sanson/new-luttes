<?php

namespace App\Entity;

use App\Repository\SharingItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SharingItemRepository::class)]
class SharingItem
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sharingItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'sharingItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SharingCategory $category = null;

    /**
     * @var Collection<int, ShareAnswer>
     */
    #[ORM\OneToMany(targetEntity: ShareAnswer::class, mappedBy: 'SharingItem', orphanRemoval: true)]
    private Collection $shareAnswers;

    public function __construct()
    {
        $this->shareAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCategory(): ?SharingCategory
    {
        return $this->category;
    }

    public function setCategory(?SharingCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, ShareAnswer>
     */
    public function getShareAnswers(): Collection
    {
        return $this->shareAnswers;
    }

    public function addShareAnswer(ShareAnswer $shareAnswer): static
    {
        if (!$this->shareAnswers->contains($shareAnswer)) {
            $this->shareAnswers->add($shareAnswer);
            $shareAnswer->setSharingItem($this);
        }

        return $this;
    }

    public function removeShareAnswer(ShareAnswer $shareAnswer): static
    {
        if ($this->shareAnswers->removeElement($shareAnswer)) {
            // set the owning side to null (unless already changed)
            if ($shareAnswer->getSharingItem() === $this) {
                $shareAnswer->setSharingItem(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SharingCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SharingCategoryRepository::class)]
class SharingCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, SharingItem>
     */
    #[ORM\OneToMany(targetEntity: SharingItem::class, mappedBy: 'category')]
    private Collection $sharingItems;

    public function __construct()
    {
        $this->sharingItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, SharingItem>
     */
    public function getSharingItems(): Collection
    {
        return $this->sharingItems;
    }

    public function addSharingItem(SharingItem $sharingItem): static
    {
        if (!$this->sharingItems->contains($sharingItem)) {
            $this->sharingItems->add($sharingItem);
            $sharingItem->setCategory($this);
        }

        return $this;
    }

    public function removeSharingItem(SharingItem $sharingItem): static
    {
        if ($this->sharingItems->removeElement($sharingItem)) {
            // set the owning side to null (unless already changed)
            if ($sharingItem->getCategory() === $this) {
                $sharingItem->setCategory(null);
            }
        }

        return $this;
    }
}

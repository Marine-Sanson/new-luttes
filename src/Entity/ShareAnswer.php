<?php

namespace App\Entity;

use App\Repository\ShareAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShareAnswerRepository::class)]
class ShareAnswer
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shareAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SharingItem $SharingItem = null;

    #[ORM\ManyToOne(inversedBy: 'shareAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSharingItem(): ?SharingItem
    {
        return $this->SharingItem;
    }

    public function setSharingItem(?SharingItem $SharingItem): static
    {
        $this->SharingItem = $SharingItem;

        return $this;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}

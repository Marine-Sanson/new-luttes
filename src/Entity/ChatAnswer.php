<?php

namespace App\Entity;

use App\Repository\ChatAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatAnswerRepository::class)]
class ChatAnswer
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'chatAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChatItem $chatItem = null;

    #[ORM\ManyToOne(inversedBy: 'chatAnswers', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChatItem(): ?ChatItem
    {
        return $this->chatItem;
    }

    public function setChatItem(?ChatItem $chatItem): static
    {
        $this->chatItem = $chatItem;

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

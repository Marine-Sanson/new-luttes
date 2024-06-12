<?php

namespace App\Entity;

use App\Repository\ChatItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatItemRepository::class)]
class ChatItem
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'chatItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    /**
     * @var Collection<int, ChatAnswer>
     */
    #[ORM\OneToMany(targetEntity: ChatAnswer::class, mappedBy: 'chatItem', orphanRemoval: true)]
    private Collection $chatAnswers;

    public function __construct()
    {
        $this->chatAnswers = new ArrayCollection();
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection<int, ChatAnswer>
     */
    public function getChatAnswers(): Collection
    {
        return $this->chatAnswers;
    }

    public function addChatAnswer(ChatAnswer $chatAnswer): static
    {
        if (!$this->chatAnswers->contains($chatAnswer)) {
            $this->chatAnswers->add($chatAnswer);
            $chatAnswer->setChatItem($this);
        }

        return $this;
    }

    public function removeChatAnswer(ChatAnswer $chatAnswer): static
    {
        if ($this->chatAnswers->removeElement($chatAnswer)) {
            // set the owning side to null (unless already changed)
            if ($chatAnswer->getChatItem() === $this) {
                $chatAnswer->setChatItem(null);
            }
        }

        return $this;
    }
}

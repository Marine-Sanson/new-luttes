<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $agreement = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Photo $Photo = null;

    /**
     * @var Collection<int, ChatItem>
     */
    #[ORM\OneToMany(targetEntity: ChatItem::class, mappedBy: 'user')]
    private Collection $chatItems;

    /**
     * @var Collection<int, ChatAnswer>
     */
    #[ORM\OneToMany(targetEntity: ChatAnswer::class, mappedBy: 'user')]
    private Collection $chatAnswers;

    /**
     * @var Collection<int, SharingItem>
     */
    #[ORM\OneToMany(targetEntity: SharingItem::class, mappedBy: 'user')]
    private Collection $sharingItems;

    /**
     * @var Collection<int, ShareAnswer>
     */
    #[ORM\OneToMany(targetEntity: ShareAnswer::class, mappedBy: 'user')]
    private Collection $shareAnswers;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'user')]
    private Collection $participations;

    public function __construct()
    {
        $this->chatItems = new ArrayCollection();
        $this->chatAnswers = new ArrayCollection();
        $this->sharingItems = new ArrayCollection();
        $this->shareAnswers = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAgreement(): ?int
    {
        return $this->agreement;
    }

    public function setAgreement(int $agreement): static
    {
        $this->agreement = $agreement;

        return $this;
    }

    public function getPhoto(): ?Photo
    {
        return $this->Photo;
    }

    public function setPhoto(?Photo $Photo): static
    {
        $this->Photo = $Photo;

        return $this;
    }

    /**
     * @return Collection<int, ChatItem>
     */
    public function getChatItems(): Collection
    {
        return $this->chatItems;
    }

    public function addChatItem(ChatItem $chatItem): static
    {
        if (!$this->chatItems->contains($chatItem)) {
            $this->chatItems->add($chatItem);
            $chatItem->setUser($this);
        }

        return $this;
    }

    public function removeChatItem(ChatItem $chatItem): static
    {
        if ($this->chatItems->removeElement($chatItem)) {
            // set the owning side to null (unless already changed)
            if ($chatItem->getUser() === $this) {
                $chatItem->setUser(null);
            }
        }

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
            $chatAnswer->setUser($this);
        }

        return $this;
    }

    public function removeChatAnswer(ChatAnswer $chatAnswer): static
    {
        if ($this->chatAnswers->removeElement($chatAnswer)) {
            // set the owning side to null (unless already changed)
            if ($chatAnswer->getUser() === $this) {
                $chatAnswer->setUser(null);
            }
        }

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
            $sharingItem->setUser($this);
        }

        return $this;
    }

    public function removeSharingItem(SharingItem $sharingItem): static
    {
        if ($this->sharingItems->removeElement($sharingItem)) {
            // set the owning side to null (unless already changed)
            if ($sharingItem->getUser() === $this) {
                $sharingItem->setUser(null);
            }
        }

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
            $shareAnswer->setUser($this);
        }

        return $this;
    }

    public function removeShareAnswer(ShareAnswer $shareAnswer): static
    {
        if ($this->shareAnswers->removeElement($shareAnswer)) {
            // set the owning side to null (unless already changed)
            if ($shareAnswer->getUser() === $this) {
                $shareAnswer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setUser($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getUser() === $this) {
                $participation->setUser(null);
            }
        }

        return $this;
    }

}

<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $urlVideo = null;

    #[ORM\Column]
    private ?bool $currentSong = false;

    #[ORM\ManyToOne(inversedBy: 'songs', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    private ?SongCategory $category = null;

    /**
     * @var Collection<int, Text>
     */
    #[ORM\OneToMany(targetEntity: Text::class, mappedBy: 'song', cascade:['persist'], orphanRemoval: true)]
    private Collection $texts;

    public function __construct()
    {
        $this->texts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUrlVideo(): ?string
    {
        return $this->urlVideo;
    }

    public function setUrlVideo(string $urlVideo): static
    {
        $this->urlVideo = $urlVideo;

        return $this;
    }

    public function isCurrentSong(): ?bool
    {
        return $this->currentSong;
    }

    public function setCurrentSong(bool $currentSong): static
    {
        $this->currentSong = $currentSong;

        return $this;
    }

    public function getCategory(): ?SongCategory
    {
        return $this->category;
    }

    public function setCategory(?SongCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Text>
     */
    public function getTexts(): Collection
    {
        return $this->texts;
    }

    public function addText(Text $text): static
    {
        if (!$this->texts->contains($text)) {
            $this->texts->add($text);
            $text->setSong($this);
        }

        return $this;
    }

    public function removeText(Text $text): static
    {
        if ($this->texts->removeElement($text)) {
            // set the owning side to null (unless already changed)
            if ($text->getSong() === $this) {
                $text->setSong(null);
            }
        }

        return $this;
    }
}

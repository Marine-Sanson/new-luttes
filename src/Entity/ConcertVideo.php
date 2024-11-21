<?php

namespace App\Entity;

use App\Repository\ConcertVideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConcertVideoRepository::class)]
class ConcertVideo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'concertVideos', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ConcertDay $concertDay = null;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getConcertDay(): ?ConcertDay
    {
        return $this->concertDay;
    }

    public function setConcertDay(?ConcertDay $concertDay): static
    {
        $this->concertDay = $concertDay;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ConcertDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConcertDayRepository::class)]
class ConcertDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, ConcertVideo>
     */
    #[ORM\OneToMany(targetEntity: ConcertVideo::class, mappedBy: 'concertDay')]
    private Collection $concertVideos;

    public function __construct()
    {
        $this->concertVideos = new ArrayCollection();
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
     * @return Collection<int, ConcertVideo>
     */
    public function getConcertVideos(): Collection
    {
        return $this->concertVideos;
    }

    public function addConcertVideo(ConcertVideo $concertVideo): static
    {
        if (!$this->concertVideos->contains($concertVideo)) {
            $this->concertVideos->add($concertVideo);
            $concertVideo->setConcertDay($this);
        }

        return $this;
    }

    public function removeConcertVideo(ConcertVideo $concertVideo): static
    {
        if ($this->concertVideos->removeElement($concertVideo)) {
            // set the owning side to null (unless already changed)
            if ($concertVideo->getConcertDay() === $this) {
                $concertVideo->setConcertDay(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

class SongDetails
{
    private int $id;

    private string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    private bool $currentSong;

    private ?int $categoryId = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
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
        if(is_null($description)) {
            $this->description = null;
          } else {
            $this->description = $description;
          }

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

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): static
    {
        $this->categoryId = $categoryId;

        return $this;
    }

}

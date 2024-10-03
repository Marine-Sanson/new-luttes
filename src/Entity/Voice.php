<?php

namespace App\Entity;

use App\Entity\CreatedAtTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoiceRepository;

#[ORM\Entity(repositoryClass: VoiceRepository::class)]
class Voice
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'voices')]
    private ?Song $song = null;

    #[ORM\Column(length: 255)]
    private ?string $voiceType = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column(length: 5)]
    private ?string $fileType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): static
    {
        $this->song = $song;

        return $this;
    }

    public function getVoiceType(): ?string
    {
        return $this->voiceType;
    }

    public function setVoiceType(string $voiceType): static
    {
        $this->voiceType = $voiceType;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function setFileType(string $fileType): static
    {
        $this->fileType = $fileType;

        return $this;
    }
}

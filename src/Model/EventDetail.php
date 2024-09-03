<?php

namespace App\Model;

class EventDetail
{

    private int $id;

    private string $date;

    private string $eventCategory;

    private string $privateDetails;

    private ?string $publicDetails;

    private string $status;

    private array $participationOui;

    private array $participationNon;

    private array $participationNsp;

    private array $participationPasrep;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getEventCategory(): string
    {
        return $this->eventCategory;
    }

    public function setEventCategory(string $eventCategory): static
    {
        $this->eventCategory = $eventCategory;

        return $this;
    }

    public function getPrivateDetails(): string
    {
        return $this->privateDetails;
    }

    public function setPrivateDetails(string $privateDetails): static
    {
        $this->privateDetails = $privateDetails;

        return $this;
    }
    
    public function getPublicDetails(): ?string
    {
        return $this->publicDetails;
    }

    public function setPublicDetails(?string $publicDetails): static
    {
        $this->publicDetails = $publicDetails;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getParticipationOui(): array
    {
        return $this->participationOui;
    }

    public function setParticipationOui(array $participationOui): static
    {
        $this->participationOui = $participationOui;

        return $this;
    }

    public function getParticipationNon(): array
    {
        return $this->participationNon;
    }

    public function setParticipationNon(array $participationNon): static
    {
        $this->participationNon = $participationNon;

        return $this;
    }

    public function getParticipationNsp(): array
    {
        return $this->participationNsp;
    }

    public function setParticipationNsp(array $participationNsp): static
    {
        $this->participationNsp = $participationNsp;

        return $this;
    }

    public function getParticipationPasrep(): array
    {
        return $this->participationPasrep;
    }

    public function setParticipationPasrep(array $participationPasrep): static
    {
        $this->participationPasrep = $participationPasrep;

        return $this;
    }

}

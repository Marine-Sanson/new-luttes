<?php

namespace App\Model;

use App\Entity\Status;
use App\Entity\EventCategory;

class EventToManage
{

    private int $id;

    private string $date;

    private EventCategory $eventCategory;

    private string $privateDetails;

    private ?string $publicDetails;

    private bool $status;

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

    public function getEventCategory(): EventCategory
    {
        return $this->eventCategory;
    }

    public function setEventCategory(EventCategory $eventCategory): static
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

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

}

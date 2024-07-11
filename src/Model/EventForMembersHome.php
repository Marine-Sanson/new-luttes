<?php

namespace App\Model;

class EventForMembersHome
{
    private int $eventId;

    private int $participationId;

    private string $category;

    private string $date;

    private string $privateDetails;

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function setEventId(int $eventId): static
    {
        $this->eventId = $eventId;

        return $this;
    }

    public function getParticipationId(): int
    {
        return $this->participationId;
    }

    public function setParticipationId(int $participationId): static
    {
        $this->participationId = $participationId;

        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

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

    public function getPrivateDetails(): string
    {
        return $this->privateDetails;
    }

    public function setPrivateDetails(string $privateDetails): static
    {
        $this->privateDetails = $privateDetails;

        return $this;
    }

}

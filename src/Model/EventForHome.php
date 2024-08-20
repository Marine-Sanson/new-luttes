<?php

namespace App\Model;

class EventForHome
{

    private string $date;

    private ?string $publicDetails = null;

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

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

}

<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class EventForHome
{

    private string $date;

    private string $publicDetails;

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPublicDetails(): string
    {
        return $this->publicDetails;
    }

    public function setPublicDetails(string $publicDetails): static
    {
        $this->publicDetails = $publicDetails;

        return $this;
    }

}

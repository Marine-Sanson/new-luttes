<?php

namespace App\Model;

class EventForAgenda
{

    private int $id;

    private string $date;

    private string $eventCategory;

    private string $privateDetails;

    private string $status;

    private int $oui;

    private int $non;

    private int $nsp;

    private int $pasrep;

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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getOui(): int
    {
        return $this->oui;
    }

    public function setOui(int $oui): static
    {
        $this->oui = $oui;

        return $this;
    }

    public function getNon(): int
    {
        return $this->non;
    }

    public function setNon(int $non): static
    {
        $this->non = $non;

        return $this;
    }

    public function getNsp(): int
    {
        return $this->nsp;
    }

    public function setNsp(int $nsp): static
    {
        $this->nsp = $nsp;

        return $this;
    }

    public function getPasrep(): int
    {
        return $this->pasrep;
    }

    public function setPasrep(int $pasrep): static
    {
        $this->pasrep = $pasrep;

        return $this;
    }
}

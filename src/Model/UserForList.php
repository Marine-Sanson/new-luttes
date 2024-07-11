<?php

namespace App\Model;

class UserForList
{
    private int $id;

    private string $email;

    private string $name;

    private ?string $tel = null;

    private int $agreement = 0;

    private ?string $photoName = null;
    
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAgreement(): int
    {
        return $this->agreement;
    }

    public function setAgreement(int $agreement): static
    {
        $this->agreement = $agreement;

        return $this;
    }

    public function getPhotoName(): ?string
    {
        return $this->photoName;
    }

    public function setPhotoName(string $photoName): static
    {
        $this->photoName = $photoName;

        return $this;
    }

}

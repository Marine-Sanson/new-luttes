<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContactRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(message: 'Cet email n\'est pas valide')]
    #[Assert\Length(
        min: 8,
        minMessage: 'L\'email doit contenir au moins {{ limit }} caractères',
        max: 255,
        maxMessage: 'L\'email ne doit pas faire plus de {{ limit }} caractères'
    )]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 8,
        minMessage: 'L\'objet doit contenir au moins {{ limit }} caractères',
        max: 255,
        maxMessage: 'L\'objet ne doit pas faire plus de {{ limit }} caractères'
    )]
    private ?string $object = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(
        min: 5,
        minMessage: 'Attention vous essayez d\'envoyer un texte vide ou presque. Ce message doit faire au moins {{ limit }} caractères',
    )]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): static
    {
        $this->object = $object;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}

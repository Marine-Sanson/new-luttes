<?php

namespace App\Service;

use App\Service\MailService;
use App\Repository\ContactRepository;

class ContactService
{
    public function __construct(
        private readonly ContactRepository $contactsRepository,
        private readonly MailService $mailService,
    ) {

    }

    public function manageContact(string $email, string $object, string $content)
    {

        $eur = new \DateTimeZone("Europe/Paris");
        $date = new \DateTime("now", $eur);
        $savedContact = $this->saveContact($email, $object, $content, $date);
        if ($savedContact)
        {

            $this->mailService->send(
                'contact@luttesenchantees35.fr',
                $email,
                $object,
                'contact',
                [
                    'mail' => $email,
                    'object' => $object,
                    'content' => $content,
                ]
            );
        }
    }

    public function saveContact($email, $object, $content, $date): bool
    {
        return $this->contactsRepository->saveContact($email, $object, $content, $date);
    }
}

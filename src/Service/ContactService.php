<?php

namespace App\Service;

use DateTimeZone;
use DateTimeImmutable;
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

        $savedContact = $this->saveContact($email, $object, $content, new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")));
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

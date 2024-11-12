<?php

namespace App\Service;

use DateTimeZone;
use DateTimeImmutable;
use App\Entity\Contact;
use App\Service\MailService;
use App\Repository\ContactRepository;

class ContactService
{
    public function __construct(
        private readonly ContactRepository $contactsRepository,
        private readonly MailService $mailService,
    ) {

    }

    public function manageContact(Contact $contact)
    {

        $savedContact = $this->saveContact($contact);
        if ($savedContact)
        {

            $this->mailService->send(
                'contact@luttesenchantees35.fr',
                $contact->getMail(),
                $contact->getObject(),
                'contact',
                [
                    'mail' => $contact->getMail(),
                    'object' => $contact->getObject(),
                    'content' => $contact->getContent(),
                ]
            );
        }
    }

    public function saveContact($contact): bool
    {
        return $this->contactsRepository->saveContact($contact);
    }
}

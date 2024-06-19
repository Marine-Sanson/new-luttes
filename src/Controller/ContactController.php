<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    public function __construct(
        private readonly ContactService $contactService
    ) {

    }

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $contact = new Contact();

        $contactForm = $this->createForm(ContactType::class, $contact);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            if($contactForm->get('result')->getData() === 7)
            {

                $this->contactService->manageContact($contact->getMail(), $contact->getObject(), $contact->getContent());
            }
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $contactForm,
        ]);

    }
}

<?php

namespace App\Controller;

use DateTimeZone;
use DateTimeImmutable;
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
        $number = rand(0,7);
        $result = $number + 2;

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            if($contactForm->get('userResult')->getData() === 7)
            {
                $contact->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")));
                $this->contactService->manageContact($contact);
                $this->addFlash('success', 'Votre message a bien été pris en compte');

                return $this->redirectToRoute('app_contact');
            }
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $contactForm,
            'number' => $number,
            'result' => $result,
        ]);

    }
}

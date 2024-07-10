<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Service\UserService;
use App\Service\EventService;
use App\Form\EventParticipationType;
use App\Service\ParticipationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParticipationController extends AbstractController
{

    public function __construct(
        private readonly EventService $eventService,
        private readonly UserService $userService,
        private readonly ParticipationService $participationService,
    ) {

    }

    #[Route('/participation/{participationId}', name: 'app_participation')]
    #[IsGranted('ROLE_USER')]
    public function index(int $participationId, Request $request): Response
    {

        $participation = $this->participationService->getParticipationById($participationId);

        $eventParticipationForm =$this->createForm(EventParticipationType::class, $participation);

        $eventParticipationForm->handleRequest($request);

        if ($eventParticipationForm->isSubmitted() && $eventParticipationForm->isValid()) {

            $now = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
            $participation
                ->setUpdatedAt(DateTimeImmutable::createFromMutable($now))
                ->setStatus($this->participationService->getStatusByName($eventParticipationForm->get('status')->getData()));

            $this->participationService->saveParticipation($participation);

            $this->addFlash('success', 'Participation enregistrée avec succès');
            return $this->redirectToRoute('app_members_home');
        }
        
        return $this->render('participation/changeParticipation.html.twig', [
            'event' => $participation->getEvent(),
            'eventParticipationForm' => $eventParticipationForm,
        ]);
    }
}

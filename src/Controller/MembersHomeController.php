<?php

namespace App\Controller;

use App\Service\UserService;
use App\Service\EventService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MembersHomeController extends AbstractController
{

    public function __construct(
        private readonly EventService $eventService,
        private readonly UserService $userService,
    ) {

    }
    
    #[Route('/accueil', name: 'app_members_home')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->userService->findUserByEmail($this->getUser()->getUserIdentifier());
        
        $eventsWithoutAnswer = $this->eventService->getEventsByParticipation($user, 4);

        $eventsDontKnow = $this->eventService->getEventsByParticipation($user, 3);

        $eventsNo = $this->eventService->getEventsByParticipation($user, 2);

        $eventsYes = $this->eventService->getEventsByParticipation($user, 1);

        // $newMessages = 

        return $this->render('members_home/members_home.html.twig', [
            'user' => $user,
            'eventsWithoutAnswer' => $eventsWithoutAnswer,
            'eventsDontKnow' => $eventsDontKnow,
            'eventsNo' => $eventsNo,
            'eventsYes' => $eventsYes,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Service\EventService;
use App\Service\ParticipationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/evenement')]
#[IsGranted('ROLE_USER')]
class EventController extends AbstractController
{
    
    public function __construct(
        private readonly EventService $eventService,
        private readonly ParticipationService $participationService
    ) {

    }

    #[Route('', name: 'app_event')]
    public function event(Request $request): Response
    {
        $eventsForAgenda = $this->eventService->getAllEventsForAgenda();

        return $this->render('event/event.html.twig', [
            'events' => $eventsForAgenda,
        ]);
    }

    #[Route('/detail/{id}', name: 'app_event_detail')]
    public function eventDetail(int $id, Request $request): Response
    {
        $eventDetail = $this->eventService->getEventDetail($id);

        return $this->render('event/eventDetail.html.twig', [
            'event' => $eventDetail,
        ]);
    }
    
    #[Route('/ajout', name: 'app_add_event')]
    #[IsGranted('ROLE_DATES')]
    public function addEvent(Request $request): Response
    {
        $event = new Event();
        $eventForm =$this->createForm(EventType::class, $event);

        $eventForm->handleRequest($request);


        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $user = $this->getUser();
            $event->setDate(date_format($eventForm->get('dateprov')->getData(),"d-m-Y"));

            $savedEvent = $this->eventService->addEvent($event, $user);

            $this->participationService->addParticipations($savedEvent);

            $this->addFlash('success', 'Évènement enregistré avec succès');
            return $this->redirectToRoute('app_members_home');
        }

        return $this->render('event/addEvent.html.twig', [
            'eventForm' => $eventForm,
        ]);
    }
}

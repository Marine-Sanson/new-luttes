<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Service\EventService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    
    public function __construct(
        private readonly EventService $eventService
    ) {

    }
    #[Route('/agenda', name: 'app_event')]
    public function event(Request $request): Response
    {
        $eventsForAgenda = $this->eventService->getAllEventsForAgenda();

        return $this->render('event/event.html.twig', [
            'events' => $eventsForAgenda,
        ]);
    }

    #[Route('/detail_evenement/{id}', name: 'app_event_detail')]
    public function eventDetail(int $id, Request $request): Response
    {
        $eventDetail = $this->eventService->getEventDetail($id);

        return $this->render('event/eventDetail.html.twig', [
            'event' => $eventDetail,
        ]);
    }
    
    #[Route('/add_event', name: 'app_add_event')]
    public function addEvent(Request $request): Response
    {
        $event = new Event();
        $eventForm =$this->createForm(EventType::class, $event);

        $eventForm->handleRequest($request);


        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $user = $this->getUser();
            $event
                ->setDate(date_format($eventForm->get('dateprov')->getData(),"d-m-Y"));

            $this->eventService->addEvent($event, $user);
        }

        return $this->render('event/addEvent.html.twig', [
            'eventForm' => $eventForm,
        ]);
    }
}

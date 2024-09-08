<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Service\EventService;
use App\Form\EventToManageType;
use App\Service\ParticipationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/evenements')]
class EventController extends AbstractController
{
    
    public function __construct(
        private readonly EventService $eventService,
        private readonly ParticipationService $participationService
    ) {

    }

    #[IsGranted('ROLE_USER')]
    #[Route('', name: 'app_event')]
    public function event(Request $request): Response
    {
        $eventsForAgenda = $this->eventService->getAllEventsForAgenda();

        return $this->render('event/event.html.twig', [
            'events' => $eventsForAgenda,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/8mars24', name: 'app_images_8mars24')]
    public function imagesDetail8mars24(): Response
    {

        return $this->render('images/images_8mars24.html.twig', [
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/detail/{id}', name: 'app_event_detail')]
    public function eventDetails(int $id, Request $request): Response
    {
        $eventDetail = $this->eventService->getEventDetail($id);

        return $this->render('event/eventDetail.html.twig', [
            'event' => $eventDetail,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/maj/{id}', name: 'app_event_update')]
    public function eventUpdateDetails(int $id, Request $request): Response
    {
        $eventToManage = $this->eventService->getEventToManage($id);

        $eventForm = $this->createForm(EventToManageType::class, $eventToManage);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {

            $event = $this->eventService->manageEvent($eventToManage);

            $this->addFlash('success', 'Nouvelle date enregistrée avec succès');
            return $this->redirectToRoute('app_manage_event');
        }

        return $this->render('event/update_event.html.twig', [
            'event' => $eventToManage,
            'eventForm' => $eventForm,
        ]);
    }

    #[IsGranted('ROLE_DATES')]
    #[Route('/suppr/{id}', name: 'app_event_delete')]
    public function deleteEvent(int $id): Response
    {

        $isEventDeleted = $this->eventService->deleteEvent($id);

        if ($isEventDeleted){
            $this->addFlash('success', 'L\'évenement à bien été supprimé');
            return $this->redirectToRoute('app_manage_event');
        } else {
            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('app_manage_event');
        }

    }
    
    #[IsGranted('ROLE_DATES')]
    #[Route('/gestion', name: 'app_manage_event')]
    public function manageEvents(): Response
    {

        $events = $this->eventService->getEventsToManage();

        return $this->render('event/manage_events.html.twig', [
            'events' => $events,
        ]);
    }

    #[IsGranted('ROLE_DATES')]
    #[Route('/ajout', name: 'app_add_event')]
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

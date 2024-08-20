<?php

namespace App\Service;

use DateTimeZone;
use App\Entity\User;
use App\Entity\Event;
use DateTimeImmutable;
use App\Model\EventDetail;
use App\Mapper\EventMapper;
use App\Model\EventForHome;
use App\Model\EventToManage;
use App\Model\EventForAgenda;
use App\Repository\EventRepository;
use App\Service\ParticipationService;
use App\Repository\EventCategoryRepository;
use App\Repository\ParticipationRepository;

class EventService
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly EventCategoryRepository $eventCategoryRepository,
        private readonly ParticipationService $participationService,
        private readonly ParticipationRepository $participationRepository,
        private readonly EventMapper $eventMapper,
    ) {

    }

    public function getEventsByParticipation(User $user, int $status): array
    {
        return $this->participationService->findEventsByParticipation($user, $status);
    }

    public function getEventById(int $eventId): Event
    {
        return $this->eventRepository->findOneById($eventId);
    }

    public function getPublicEvents()
    {
        $publicEvents = $this->eventRepository->findByStatus(0);
        return array_map(
            function (Event $event) {
                return $this->getEventForHome($event);
            },
            $publicEvents
        );
    }

    public function getEventForHome(Event $event): EventForHome
    {
    return $this->eventMapper->transformToEventsForHome($event);
    }

    public function getAllEventsForAgenda()
    {
        $events = $this->getAllEvents();

        return array_map(
            function (Event $event) {
                return $this->getEventForAgenda($event);
            },
            $events
        );
    }

    public function getEventsToManage()
    {
        $events = $this->getAllEvents();

        return array_map(
            function (Event $event) {
                return $this->eventMapper->transformToEventToManage($event);
            },
            $events
        );
    }

    public function getEventToManage(int $id): EventToManage
    {

        $event = $this->getEventById($id);

        return $this->eventMapper->transformToEventToManage($event);

    }

    public function manageEvent(EventToManage $eventToManage): Event
    {
        $eventToUpdate = $this->eventRepository->findOneById($eventToManage->getId());

        if ($eventToUpdate->getEventCategory() !== $eventToManage->getEventCategory()){
            $updatedEvent = $eventToUpdate->setEventCategory($eventToManage->getEventCategory());
        }

        if ($eventToUpdate->getPrivateDetails() !== $eventToManage->getPrivateDetails()){
            $updatedEvent = $eventToUpdate->setPrivateDetails($eventToManage->getPrivateDetails());
        }

        if ($eventToUpdate->getPublicDetails() !== $eventToManage->getPublicDetails()){
            $publicDetails = null;
            if ($eventToManage->getPublicDetails()){
                $publicDetails =  $eventToManage->getPublicDetails();
            }
            $updatedEvent = $eventToUpdate->setPublicDetails($publicDetails);
        }

        if ($eventToUpdate->getStatus() !== $eventToManage->getStatus()){
            $updatedEvent = $eventToUpdate->setStatus($eventToManage->getStatus());
        }

        if ($eventToUpdate->getEventCategory() == $eventToManage->getEventCategory()
            and $eventToUpdate->getPrivateDetails() == $eventToManage->getPrivateDetails()
            and $eventToUpdate->getPublicDetails() == $eventToManage->getPublicDetails()
            and $eventToUpdate->getStatus() == $eventToManage->getStatus()
        ){
            $updatedEvent = $eventToUpdate;
        }

        return $this->saveEvent($updatedEvent);

    }

    public function getEventForAgenda(Event $event): EventForAgenda
    {
        $cat = $this->eventCategoryRepository->findOneById($event->getEventCategory())->getName();
        $oui = $this->participationService->countParticipation($event->getId(), 1);
        $non = $this->participationService->countParticipation($event->getId(), 2);
        $nsp = $this->participationService->countParticipation($event->getId(), 3);
        $pasrep = $this->participationService->countParticipation($event->getId(), 4);

        return $this->eventMapper->transformToEventForAgenda($event, $cat, $oui, $non, $nsp, $pasrep);
    }

    public function getAllEvents(): array
    {
        return $this->eventRepository->findAllEvents();
    }

    public function getAllEventsIds(): array
    {
        $events = $this->eventRepository->findAll();

        return array_map(
            function (Event $event) {
                return $event->getId();
            },
            $events
        );
    }

    public function getEventDetail(int $id)
    {
        $event = $this->eventRepository->findOneById($id);

        $cat = $this->eventCategoryRepository->findOneById($event->getEventCategory())->getName();
        $status = "privé";
        if ($event->getStatus() === 0)
        {
            $status = "public";
        }
        $participationOui = $this->participationService->findParticipationByStatus($id, 1);
        $participationNon = $this->participationService->findParticipationByStatus($id, 2);
        $participationNsp = $this->participationService->findParticipationByStatus($id, 3);
        $participationPasrep = $this->participationService->findParticipationByStatus($id, 4);

        return (new EventDetail())
            ->setId($id)
            ->setDate($event->getDate())
            ->setEventCategory($cat)
            ->setPrivateDetails($event->getPrivateDetails())
            ->setPublicDetails($event->getPublicDetails())
            ->setStatus($status)
            ->setParticipationOui($participationOui)
            ->setParticipationNon($participationNon)
            ->setParticipationNsp($participationNsp)
            ->setParticipationPasrep($participationPasrep)
        ;
    }

    public function addEvent(Event $event, User $user)
    {
        $timestamp = strtotime($event->getDate());

        $event->setDate($event->getDate())
              ->setTimestamp($timestamp)
              ->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")))
              ->setUpdatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")))
              ->setUpdateUser($user);

        return $this->saveEvent($event);
 
    }

    public function saveEvent(Event $event): Event
    {
        return $this->eventRepository->saveEvent($event);
    }

    public function deleteEvent(int $id): bool
    {
        $event = $this->eventRepository->findOneById($id);

        if ($event === null) {
            return false;
        }

        $this->eventRepository->deleteEvent($event);
        return true;
    }

}

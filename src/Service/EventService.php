<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Event;
use DateTimeImmutable;
use App\Model\EventDetail;
use App\Mapper\EventMapper;
use App\Model\EventForAgenda;
use App\Repository\EventRepository;
use App\Service\ParticipationService;
use App\Repository\EventCategoryRepository;

class EventService
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly EventCategoryRepository $eventCategoryRepository,
        private readonly ParticipationService $participationService,
        private readonly EventMapper $eventMapper,
    ) {

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

    public function getEventForAgenda(Event $event): EventForAgenda
    {
        $cat = $this->eventCategoryRepository->findOneById($event->getEventCategory())->getName();
        $oui = $this->participationService->countParticipation(1);
        $non = $this->participationService->countParticipation(2);
        $nsp = $this->participationService->countParticipation(3);
        $pasrep = $this->participationService->countParticipation(4);

        return $this->eventMapper->transformToEventForAgenda($event, $cat, $oui, $non, $nsp, $pasrep);
    }

    public function getAllEvents()
    {
        return $this->eventRepository->findAll();
    }

    public function getAllCats()
    {
        return $this->eventCategoryRepository->findAll();
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
        $participationOui = $this->participationService->findParticipationByStatus(1);
        $participationNon = $this->participationService->findParticipationByStatus(2);
        $participationNsp = $this->participationService->findParticipationByStatus(3);
        $participationPasrep = $this->participationService->findParticipationByStatus(4);

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

        $eur = new \DateTimeZone("Europe/Paris");
        $now = new \DateTime("now", $eur);

        $timestamp = strtotime($event->getDate());

        $event
            ->setDate($event->getDate())
            ->setTimestamp($timestamp)
            ->setCreatedAt(DateTimeImmutable::createFromMutable($now))
            ->setUpdatedAt(DateTimeImmutable::createFromMutable($now))
            ->setUpdateUser($user);

        $this->saveEvent($event);
 
    }

    public function saveEvent(Event $event): void
    {
        $this->eventRepository->saveEvent($event);
    }
}

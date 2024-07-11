<?php

namespace App\Service;

use DateTime;
use DateTimeZone;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Status;
use DateTimeImmutable;
use App\Mapper\EventMapper;
use App\Service\UserService;
use App\Entity\Participation;
use App\Repository\StatusRepository;
use App\Repository\ParticipationRepository;

class ParticipationService
{
    public function __construct(
        private readonly ParticipationRepository $participationRepository,
        private readonly UserService $userService,
        private readonly StatusRepository $statusRepository,
        private readonly EventMapper $eventMapper
    ) {

    }

    public function addParticipations(Event $event)
    {
        $users = $this->userService->getAllUsers();
        $eur = new DateTimeZone("Europe/Paris");
        $now = new DateTime("now", $eur);
        $status = $this->statusRepository->findOneById(4);

        foreach ($users as $key => $user){
            $participation = (new Participation())
                    ->setEvent($event)
                    ->setStatus($status)
                    ->setUpdatedAt(DateTimeImmutable::createFromMutable($now))
                    ->setUser($user)
            ;
            $this->participationRepository->saveParticipation($participation);
        }

    }

    public function getParticipationById(int $participationId): Participation
    {
        return $this->participationRepository->findOneById($participationId);
    }

    public function saveParticipation(Participation $participation)
    {
        $this->participationRepository->saveParticipation($participation);
    }

    public function countParticipation(int $eventId, int $id): int
    {
        return $this->participationRepository->countParticipation($eventId, $id);
    }

    public function findParticipationByStatus(int $eventId, int $status): ?array
    {
        $participations = $this->participationRepository->findParticipationByStatus($eventId, $status);
        if($participations){
            return array_map(
                function (Participation $participation) {
                    return $this->userService->findUserNameById($participation->getUser()->getid());
                },
                $participations
            );
        }
        return [];

    }

    public function findEventsByParticipation(User $user, int $status): array
    {
        $participations = $this->participationRepository->findByParticipationStatus($user, $status);
        if($participations){
            return array_map(
                function (Participation $participation) {
                    $event = $participation->getEvent();
                    $participationId = $participation->getId();
                    return $this->eventMapper->transformToEventForMembersHome($event, $participationId);
                },
                $participations
            );
        }
        return [];
    }

    public function getStatusByName(string $name): Status
    {
        return $this->statusRepository->findOneByName($name);
    }

}

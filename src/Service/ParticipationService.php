<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Status;
use App\Entity\Participation;
use App\Repository\UserRepository;
use App\Repository\ParticipationRepository;

class ParticipationService
{
    public function __construct(
        private readonly ParticipationRepository $participationRepository,
        private readonly UserRepository $userRepository,
    ) {

    }

    public function countParticipation(int $eventId, int $id)
    {
        return $this->participationRepository->countParticipation($eventId, $id);
    }

    public function findParticipationByStatus(int $eventId, int $status): ?array
    {
        $participations = $this->participationRepository->findParticipationByStatus($eventId, $status);
        if($participations){
            return array_map(
                function (Participation $participation) {
                    return $this->userRepository->findOneById($participation->getUser())->getName();
                },
                $participations
            );
        }
        return [];

    }

}

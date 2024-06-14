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

    public function countParticipation(int $id)
    {
        return $this->participationRepository->countParticipation($id);
    }

    public function findParticipationByStatus(int $status): ?array
    {
        $userIds = $this->participationRepository->findParticipationByStatus($status);
        return array_map(
            function (Participation $userId) {
                return $this->userRepository->findOneById($userId->getId())->getName();
            },
            $userIds
        );

    }

}

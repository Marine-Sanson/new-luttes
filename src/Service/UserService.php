<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\ParticipationRepository;

class UserService
{
    public function __construct(
        private readonly ParticipationRepository $participationRepository,
        private readonly UserRepository $userRepository,
    ) {

    }

    public function getAllUsersIds(): array
    {
        return $this->userRepository->findAll();
    }

    public function findUserNameById(int $id): string
    {
        return $this->userRepository->findOneById($id)->getName();
    }
}

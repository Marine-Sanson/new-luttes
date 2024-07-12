<?php

namespace App\Service;

use DateTimeZone;
use App\Entity\User;
use DateTimeImmutable;
use App\Mapper\UserMapper;
use App\Entity\Participation;
use App\Repository\UserRepository;
use App\Repository\StatusRepository;
use App\Repository\ParticipationRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly ParticipationRepository $participationRepository,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserMapper $userMapper,
        private readonly StatusRepository $statusRepository,
    ) {

    }

    public function manageNewUser(string $email, string $role, string $plainPassword, string $name, ?string $tel, int $agreement, array $events): User
    {
        $arrayRole = ['ROLE_USER'];
        if($role === 'DATES'){
            $arrayRole = [];
            $arrayRole = ['ROLE_DATES'];
        }
        if($role === 'CHANTS'){
            $arrayRole = [];
            $arrayRole = ['ROLE_CHANTS'];
        }
        if($role === 'ADMIN'){
            $arrayRole = [];
            $arrayRole = ['ROLE_ADMIN'];
        }

        $now = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));

        $user = (new User())
            ->setEmail($email)
            ->setRoles($arrayRole)
            ->setName($name)
            ->setAgreement($agreement)
            ->setCreatedAt($now)
            ->setLastConnection($now)
        ;

        if($tel){
            $user->setTel($tel);
        }

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $plainPassword
            )
        );

        $newUser = $this->userRepository->saveNewUser($user);

        $status = $this->statusRepository->findOneById(4);

        foreach($events as $event){
            $participation = (new Participation())
                ->setEvent($event)
                ->setStatus($status)
                ->setUser($newUser)
                ->setUpdatedAt($now)
            ;
            $this->participationRepository->saveParticipation($participation);
        }

        return $newUser;
    }

    public function saveLastConnection(?User $user): void
    {
        $this->userRepository->saveUser($user);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function getUsersForList(): array
    {
        $users = $this->getAllUsers();

        return array_map(
            function (User $user) {
                return $this->userMapper->transformToUserForList($user);
            },
            $users
        );
    }

    public function findUserNameById(int $id): string
    {
        return $this->userRepository->findOneById($id)->getName();
    }

    public function findUserById(int $id): ?User
    {
        return $this->userRepository->findOneById($id);
    }

    public function saveUser(User $user): void{
        $this->userRepository->saveUser($user);
    }

    Public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneByEmail($email);
    }

}

<?php

namespace App\Service;

use DateTimeZone;
use App\Entity\User;
use DateTimeImmutable;
use App\Mapper\UserMapper;
use App\Repository\UserRepository;
use App\Repository\ParticipationRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly ParticipationRepository $participationRepository,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserMapper $userMapper,
    ) {

    }

    // public function register(UserRegister $userRegister): string
    // {

    //     $user = (new User())
    //         ->setUsername($userRegister->getUsername())
    //         ->setEmail($userRegister->getEmail());

    //     $user->setPassword(
    //         $this->userPasswordHasher->hashPassword(
    //             $user,
    //             $userRegister->getPlainPassword()
    //         )
    //     );

    //     $userCreated = $this->userRepository->saveUser($user);

    public function manageNewUser(string $email, string $role, string $plainPassword, string $name, ?string $tel, int $agreement): User
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

        $user = (new User())
            ->setEmail($email)
            ->setRoles($arrayRole)
            ->setName($name)
            ->setAgreement($agreement)
            ->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")))
            ->setLastConnection(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")))
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

        return $this->userRepository->saveNewUser($user);
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

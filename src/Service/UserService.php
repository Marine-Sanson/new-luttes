<?php

namespace App\Service;

use App\Entity\User;
use DateTimeImmutable;
use App\Repository\UserRepository;
use App\Repository\ParticipationRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly ParticipationRepository $participationRepository,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
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

        $eur = new \DateTimeZone("Europe/Paris");
        $now = new \DateTime("now", $eur);

        $user = (new User())
            ->setEmail($email)
            ->setRoles($arrayRole)
            ->setName($name)
            ->setAgreement($agreement)
            ->setCreatedAt(DateTimeImmutable::createFromMutable($now))
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

    public function getAllUsersIds(): array
    {
        return $this->userRepository->findAll();
    }

    public function findUserNameById(int $id): string
    {
        return $this->userRepository->findOneById($id)->getName();
    }
}

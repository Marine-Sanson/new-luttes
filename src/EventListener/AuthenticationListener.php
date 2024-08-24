<?php

namespace App\EventListener;

use DateTimeZone;
use App\Entity\User;
use DateTimeImmutable;
use App\Service\UserService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

final class AuthenticationListener
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[AsEventListener(event: 'security.interactive_login')]
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        if ($user instanceof User) {
            $user->setPreviousConnection($user->getLastConnection());
            $user->setLastConnection(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")));
            $this->userService->saveUser($user);
        }
    }

}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use App\Service\EventService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/membre')]
#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly EventService $eventService,
    ) {

    }

    #[Route('', name: 'app_user_list')]
    public function listUser(): Response
    {
        $usersForList = $this->userService->getUsersForList();

        return $this->render('user/list.html.twig', [
            'usersForList' => $usersForList,
        ]);
    }

    #[Route('/ajout', name: 'app_new_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function addUser(Request $request): Response
    {
        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $events = $this->eventService->getAllEvents();

            $this->userService->manageNewUser(
                $user->getEmail(),
                $userForm->get('role')->getData(),
                $userForm->get('plainPassword')->getData(),
                $user->getName(),
                $user->getTel(),
                $user->getAgreement(),
                $events
            );

            $this->addFlash('success', 'Nouvelle membre enregistrée avec succès');
            return $this->redirectToRoute('app_user_list');

        }

        return $this->render('user/user.html.twig', [
            'userForm' => $userForm,
        ]);

    }
}

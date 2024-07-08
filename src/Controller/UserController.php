<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/membre')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ) {

    }

    #[Route('/ajout', name: 'app_new_user')]
    public function index(Request $request): Response
    {
        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $this->userService->manageNewUser(
                $user->getEmail(),
                $userForm->get('role')->getData(),
                $userForm->get('plainPassword')->getData(),
                $user->getName(),
                $user->getTel(),
                $user->getAgreement()
            );
        }

        return $this->render('user/user.html.twig', [
            'userForm' => $userForm,
        ]);

    }
}

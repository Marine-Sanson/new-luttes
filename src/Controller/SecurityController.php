<?php

namespace App\Controller;

use App\Service\JWTService;
use App\Service\MailService;
use App\Service\UserService;
use App\Form\ResetPasswordType;
use App\Form\ForgotPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly JWTService $jwt,
        private readonly MailService $mailService,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {

    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
    #[Route(path: '/mot-de-passe-oublie', name: 'app_forgotten_password')]
    public function forgottenPassword(Request $request): Response
    {
        $forgottenPasswordForm = $this->createForm(ForgotPasswordType::class);
        $forgottenPasswordForm->handleRequest($request);

        if($forgottenPasswordForm->isSubmitted() && $forgottenPasswordForm->isValid()){
            $user = $this->userService->findUserByEmail($forgottenPasswordForm->get('email')->getData());
            if($user){

                // Header
                $header = [
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ];

                // Payload
                $payload = [
                    'user_id' => $user->getId()
                ];

                // On génère le token
                $token = $this->jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

                // On génère l'URL vers reset_password
                $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // Envoyer l'e-mail
                $this->mailService->send(
                    'contact@luttesenchantees35.fr',
                    $user->getEmail(),
                    'Récupération de mot de passe sur le site Luttes Enchantées',
                    'password_reset',
                    ['user' => $user, 'url'=>$url]
                );

                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'forgottenPasswordForm' => $forgottenPasswordForm,
        ]);
    }

    #[Route(path: '/mot-de-passe-oublie/{token}', name: 'app_reset_password')]
    public function resetPassword(string $token, Request $request): Response
    {
        if($this->jwt->isValid($token) && !$this->jwt->isExpired($token) && $this->jwt->check($token, $this->getParameter('app.jwtsecret'))){
            // Le token est valide
            // On récupère les données (payload)
            $payload = $this->jwt->getPayload($token);
            
            // On récupère le user
            $user = $this->userService->findUserById($payload['user_id']);

            if($user){
                $ResetPasswordForm = $this->createForm(ResetPasswordType::class);

                $ResetPasswordForm->handleRequest($request);

                if($ResetPasswordForm->isSubmitted() && $ResetPasswordForm->isValid()){
                    if($ResetPasswordForm->get('password')->getData() !== $ResetPasswordForm->get('password2')->getData()){
                        $this->addFlash('danger', 'Les 2 mots de passe ne sont pas identiques');
                        return $this->redirectToRoute('app_login');
                    }
                    $user->setPassword(
                        $this->passwordHasher->hashPassword($user, $ResetPasswordForm->get('password')->getData())
                    );

                    $this->userService->saveUser($user);

                    $this->addFlash('success', 'Mot de passe changé avec succès');
                    return $this->redirectToRoute('app_login');
                }
                return $this->render('security/reset_password.html.twig', [
                    'resetPasswordForm' => $ResetPasswordForm->createView()
                ]);
            }
        }
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

}

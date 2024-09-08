<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/imagesConcerts')]
#[IsGranted('ROLE_USER')]
class ImagesController extends AbstractController
{
    public function __construct(
    ) {

    }

    #[Route('', name: 'app_images_concerts')]
    public function index(): Response
    {
        return $this->render('images/images.html.twig', [
        ]);
    }

    #[Route('/frac', name: 'app_images_frac')]
    public function displayImagesFrac(): Response
    {
        return $this->render('images/images_frac.html.twig', [
        ]);
    }

    #[Route('/pf0624', name: 'app_images_pf0624')]
    public function displayImagesPf0624(): Response
    {
        return $this->render('images/images_pf0624.html.twig', [
        ]);
    }

    #[Route('/8mars24', name: 'app_images_8mars24')]
    public function imagesDetail8mars24(): Response
    {

        return $this->render('images/images_8mars24.html.twig', [
        ]);
    }

    #[Route('/musiciennes', name: 'app_images_musiciennes')]
    public function imagesDetailMusiciennes(): Response
    {

        return $this->render('images/images_musiciennes.html.twig', [
        ]);
    }

    #[Route('/hfr0623', name: 'app_images_hfr0623')]
    public function imagesDetailHfr0623(): Response
    {

        return $this->render('images/images_hfr0623.html.twig', [
        ]);
    }

    #[Route('/pf0323', name: 'app_images_pf0323')]
    public function displayImagesPf0323(): Response
    {
        return $this->render('images/images_pf0323.html.twig', [
        ]);
    }

    #[Route('/cc0622', name: 'app_images_cc0622')]
    public function displayImagesCc0622(): Response
    {
        return $this->render('images/images_cc0622.html.twig', [
        ]);
    }

}

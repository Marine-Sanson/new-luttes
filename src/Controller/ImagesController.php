<?php

namespace App\Controller;

use App\Service\ConcertService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/imagesConcerts')]
#[IsGranted('ROLE_USER')]
class ImagesController extends AbstractController
{
    public function __construct(
        private readonly ConcertService $concertService,
    ) {

    }

    #[Route('', name: 'app_images_concerts')]
    public function index(): Response
    {
        return $this->render('images/images.html.twig', [
        ]);
    }

    #[Route('/debout-s1', name: 'app_images_debout-s1')]
    public function displayImagesDeboutSaison1(): Response
    {
        return $this->render('images/images_debout-s1.html.twig', [
        ]);
    }

    #[Route('/biblio150325', name: 'app_images_biblio150325')]
    public function displayImagesBiblio150325(): Response
    {
        return $this->render('images/images_biblio150325.html.twig', [
        ]);
    }

    #[Route('/manif080325', name: 'app_images_manif080325')]
    public function displayImagesManif080325(): Response
    {
        return $this->render('images/images_manif080325.html.twig', [
        ]);
    }
    
    #[Route('/soutien', name: 'app_images_soutien041024')]
    public function displayImagesSoutien(): Response
    {
        return $this->render('images/images_soutien041024.html.twig', [
        ]);
    }

    #[Route('/repe24', name: 'app_images_répé_24')]
    public function displayImagesRepe24(): Response
    {
        return $this->render('images/images_répé_24.html.twig', [
        ]);
    }

    #[Route('/frac2024', name: 'app_images_frac2024')]
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

    #[Route('/repe23', name: 'app_images_répé_23')]
    public function displayImagesRepe23(): Response
    {
        return $this->render('images/images_répé_23.html.twig', [
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

    #[Route('/{videoId}', name: 'app_videoConcertPortrait')]
    public function displayConcertVideoPortrait(int $videoId): Response
    {
        $video = $this->concertService->findVideo($videoId);

        $concertName = $video->getConcertDay()->getName();
        $videoName = $video->getName();
        $songName = $video->getTitle();

        return $this->render('images/videoConcertPortrait.html.twig', [
            'concertName' => $concertName,
            'videoName' => $videoName,
            'songName' => $songName,
        ]);
    }

    #[Route('/{videoId}', name: 'app_videoConcertPaysage')]
    public function displayConcertVideoPaysage(int $videoId): Response
    {
        $video = $this->concertService->findVideo($videoId);

        $concertName = $video->getConcertDay()->getName();
        $videoName = $video->getName();
        $songName = $video->getTitle();

        return $this->render('images/videoConcertPaysage.html.twig', [
            'concertName' => $concertName,
            'videoName' => $videoName,
            'songName' => $songName,
        ]);
    }

}

<?php

namespace App\Controller;

use App\Service\EventService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly EventService $eventService
    ) {

    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $publicEvents = $this->eventService->getPublicEvents();

        // A faire *******************************************************************
        // $currentSong = $this->songService->getCurrentSong(;)

        return $this->render('home/home.html.twig', [
            'events' => $publicEvents
        ]);
    }
}

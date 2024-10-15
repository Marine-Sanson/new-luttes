<?php

namespace App\Controller;

use App\Service\SongService;
use App\Service\EventService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly EventService $eventService,
        private readonly SongService $songService
    ) {

    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $publicEvents = $this->eventService->getPublicEvents();

        $currentSongs = $this->songService->getCurrentSong();

        return $this->render('home/home.html.twig', [
            'events' => $publicEvents,
            'currentSongs' => $currentSongs
        ]);
    }
}

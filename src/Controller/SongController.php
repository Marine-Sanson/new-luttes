<?php

namespace App\Controller;

use DateTimeZone;
use App\Entity\Song;
use App\Entity\Text;
use App\Entity\Voice;
use App\Form\SongType;
use App\Form\TextType;
use DateTimeImmutable;
use App\Form\VoiceType;
use App\Form\SongsVideoType;
use App\Service\SongService;
use App\Form\SongDetailsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[IsGranted('ROLE_USER')]
#[Route('/chants')]
class SongController extends AbstractController
{
    public function __construct(
        private readonly SongService $songService,
        private readonly SluggerInterface $slugger
    ){

    }

    #[Route('', name: 'app_song')]
    public function displaySongs(): Response
    {
        $outOfCatSongs = $this->songService->getSongsWithoutCats();
        $currentYearSongs = $this->songService->getSongsByCats(1);
        $sharedSongs = $this->songService->getSongsByCats(2);
        $oldSongs = $this->songService->getSongsByCats(3);
        $livrets = $this->songService->getSongsByCats(4);


        return $this->render('song/song.html.twig', [
            'outOfCatSongs' => $outOfCatSongs,
            'currentYearSongs' => $currentYearSongs,
            'sharedSongs' => $sharedSongs,
            'oldSongs' => $oldSongs,
            'livrets' => $livrets,
        ]);
    }

    #[Route('/detail/{id}', name: 'app_song_details')]
    public function displaySongDetails(int $id): Response
    {
        $song = $this->songService->getSongDetails($id);
        $texts = $this->songService->getSongTexts($this->songService->getSongById($id));
        $voices = $this->songService->getSongVoices($this->songService->getSongById($id));

        return $this->render('song/song_details.html.twig', [
            'song' => $song,
            'texts' => $texts,
            'voices' => $voices,
        ]);
    }

    #[IsGranted('ROLE_CHANTS')]
    #[Route('/gestion', name: 'app_manage_songs')]
    public function manageSongs(): Response
    {
        $outOfCatSongs = $this->songService->getSongsWithoutCats();
        $currentYearSongs = $this->songService->getSongsByCats(1);
        $sharedSongs = $this->songService->getSongsByCats(2);
        $oldSongs = $this->songService->getSongsByCats(3);
        $livrets = $this->songService->getSongsByCats(4);

        return $this->render('song/manage_songs.html.twig', [
            'outOfCatSongs' => $outOfCatSongs,
            'currentYearSongs' => $currentYearSongs,
            'sharedSongs' => $sharedSongs,
            'oldSongs' => $oldSongs,
            'livrets' => $livrets,
        ]);
    }
    
    #[IsGranted('ROLE_CHANTS')]
    #[Route('/ajout', name: 'app_new_song')]
    public function addSong(Request $request): Response
    {
        $song = new Song();
        $songForm = $this->createForm(SongType::class, $song);

        $songForm->handleRequest($request);

        if ($songForm->isSubmitted() && $songForm->isValid()) {
            
            $newSong = $this->songService->manageNewSong($song);

            $this->addFlash('success', 'Nouveau chant enregistré avec succès');
            return $this->redirectToRoute('app_manage_songs');
        }

        return $this->render('song/add_song.html.twig', [
            'songForm' => $songForm,
        ]);
    }

    #[IsGranted('ROLE_CHANTS')]
    #[Route('/maj/{id}', name: 'app_update_song')]
    public function updateSong(int $id, Request $request): Response
    {

        $song = $this->songService->getSongDetails($id);
        $songDetailsForm = $this->createForm(SongDetailsType::class, $song);
        $songDetailsForm->handleRequest($request);
        if ($songDetailsForm->isSubmitted() && $songDetailsForm->isValid()) {
            $song = $this->songService->manageSong($song);

            $this->addFlash('success', 'Chant bien mis à jour');
            return $this->redirectToRoute('app_manage_songs');
        }

        return $this->render('song/update_song.html.twig', [
            'song' => $song,
            'songDetailsForm' => $songDetailsForm,
        ]);
    }

    #[IsGranted('ROLE_CHANTS')]
    #[Route('/addVideo/{id}', name: 'app_add_video_song')]
    public function addVideo(int $id, Request $request): Response
    {

        $song = $this->songService->getSongById($id);
        $songsVideoType = $this->createForm(SongsVideoType::class, $song);
        $songsVideoType->handleRequest($request);
        if ($songsVideoType->isSubmitted() && $songsVideoType->isValid()) {
            $song = $this->songService->addVideo($song,$songsVideoType->get('urlVideo')->getData());

            $this->addFlash('success', 'Vidéo enregistrée enregistré avec succès');
            return $this->redirectToRoute('app_manage_songs');
        }

        return $this->render('song/add_song_video.html.twig', [
            'song' => $song,
            'songsVideoType' => $songsVideoType,
        ]);
    }

    #[IsGranted('ROLE_CHANTS')]
    #[Route('/addText/{id}', name: 'app_add_text_song')]
    public function addText(int $id, Request $request, #[Autowire('%kernel.project_dir%/public/uploads/texts')] string $textDirectory): Response
    {
        $song = $this->songService->getSongById($id);
        $textSongForm = $this->createForm(TextType::class);
        $textSongForm->handleRequest($request);
        if ($textSongForm->isSubmitted() && $textSongForm->isValid()) {

            $uploadedText = $textSongForm->get(name: 'text')->getData();

            if ($uploadedText) {

                $originalFilename = pathinfo($uploadedText->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugger->slug($originalFilename);
                $extension = $uploadedText->guessExtension();
                $newFilename = $safeFilename.'-'.uniqid().'.'.$extension;

                // Move the file to the directory where brochures are stored
                try {

                    $uploadedText->move($textDirectory, $newFilename);
                } catch (FileException $e) {
                    echo 'Une erreur est survenue: ',  $e->getMessage(), "\n";
                    // ... handle exception if something happens during file upload

                }

                $text = (new Text())
                    ->setSong($song)
                    ->setOriginalName($originalFilename)
                    ->setFileName($newFilename)
                    ->setFileType($extension)
                    ->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")))
                ;
            
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $song->addText($text);
                $this->songService->saveSongText($text);

            }

            // ... persist the $product variable or any other work

            $this->addFlash('success', 'Nouveau text enregistré avec succès');
            return $this->redirectToRoute('app_manage_songs');
        }

        return $this->render('song/add_song_text.html.twig', [
            'song' => $song,
            'textSongForm' => $textSongForm,
        ]);
    }

    #[IsGranted('ROLE_CHANTS')]
    #[Route('/addVoice/{id}', name: 'app_add_voice_song')]
    public function addVoice(int $id, Request $request, #[Autowire('%kernel.project_dir%/public/uploads/voices')] string $voiceDirectory): Response
    {
        $song = $this->songService->getSongById($id);
        $voiceSongForm = $this->createForm(VoiceType::class);
        $voiceSongForm->handleRequest($request);
        if ($voiceSongForm->isSubmitted() && $voiceSongForm->isValid()) {

            $uploadedvoice = $voiceSongForm->get(name: 'voice')->getData();

            if ($uploadedvoice) {
                $voiceType = $voiceSongForm->get(name: 'type')->getData();
                if ($voiceType === 'autre'){
                    $voiceType = $voiceSongForm->get(name: 'other')->getData();
                }

                $originalFilename = pathinfo($uploadedvoice->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugger->slug($originalFilename);
                $extension = $uploadedvoice->guessExtension();
                $newFilename = $safeFilename.'-'.uniqid().'.'.$extension;

                // Move the file to the directory where brochures are stored
                try {

                    $uploadedvoice->move($voiceDirectory, $newFilename);
                } catch (FileException $e) {
                    echo 'Une erreur est survenue: ',  $e->getMessage(), "\n";
                    // ... handle exception if something happens during file upload

                }

                $voice = (new Voice())
                    ->setSong($song)
                    ->setVoiceType($voiceType)
                    ->setOriginalName($originalFilename)
                    ->setFileName($newFilename)
                    ->setFileType($extension)
                    ->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")))
                ;
            
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $song->addVoice($voice);
                $this->songService->saveSongVoice($voice);

            }

            // ... persist the $product variable or any other work

            $this->addFlash('success', 'Nouvelle voix enregistrée avec succès');
            return $this->redirectToRoute('app_manage_songs');
        }

        return $this->render('song/add_song_voice.html.twig', [
            'song' => $song,
            'voiceSongForm' => $voiceSongForm,
        ]);
    }

}

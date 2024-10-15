<?php

namespace App\Service;

use DateTimeZone;
use App\Entity\Song;
use App\Entity\Text;
use App\Entity\Voice;
use DateTimeImmutable;
use App\Mapper\SongMapper;
use App\Model\SongDetails;
use App\Repository\SongRepository;
use App\Repository\TextRepository;
use App\Repository\VoiceRepository;
use App\Repository\SongCategoryRepository;



class SongService
{
    public function __construct(
        private readonly SongMapper $songMapper,
        private readonly SongRepository $songRepository,
        private readonly TextRepository $textRepository,
        private readonly VoiceRepository $voiceRepository,
        private readonly SongCategoryRepository $songCategoryRepository
    ) {

    }

    public function manageNewSong(Song $newSong)
    {
        $newSong->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone("Europe/Paris")));

        return $this->songRepository->saveSong($newSong);
    }

    public function getSongsByCats(int $id)
    {
        $songs = $this->songRepository->findByCategory($this->songCategoryRepository->findOneById($id));

        return array_map(
            function (Song $song) {
                return $this->songMapper->transformToReduceSong($song);
            },
            $songs
        );

    }

    public function getCurrentSong()
    {
        return $this->songRepository->findByCurrentSong(true);
    }

    public function getSongsWithoutCats(): ?array
    {
        $songs = $this->songRepository->findSongsWithoutCategory();

        return array_map(
            function (Song $song) {
                return $this->songMapper->transformToReduceSong($song);
            },
            $songs
        );

    }

    public function getSongDetails(int $id): SongDetails
    {

        return $this->songMapper->transformToSongDetails($this->songRepository->findOneById($id));

    }

    public function getSongTexts(Song $song): array
    {
        return $this->textRepository->findBySong($song);
    }
    
    public function getSongVoices(Song $song): array
    {
        return $this->voiceRepository->findBySong($song);
    }

    public function saveSongText(Text $text): Text
    {
        return $this->textRepository->saveText($text);
    }

    public function saveSongVoice(Voice $voice): Voice
    {
        return $this->voiceRepository->saveVoice($voice);
    }

    public function addVideo(Song $song, string $urlVideo): Song
    {
        $songToUpdate = $this->songRepository->findOneById($song->getId());

        $songToUpdate->setUrlVideo($urlVideo);

        return $this->songRepository->saveSong($songToUpdate);
    }

    public function manageSong(SongDetails $song): Song
    {

        $songToUpdate = $this->songRepository->findOneById($song->getId());

        $updatedSong = $songToUpdate
            ->setTitle($song->getTitle())
            ->setCurrentSong($song->isCurrentSong())
        ;

        if($song->getDescription()){
            $updatedSong->setDescription($song->getDescription());
        }
        if(!$song->getDescription()){
            $updatedSong->setDescription(null);
        }

        if($song->getCategoryId()){
            $updatedSong->setCategory($this->songCategoryRepository->findOneById($song->getCategoryId()));
        }

        return $this->songRepository->saveSong($updatedSong);
    }

    public function getSongById($id): Song
    {
        return $this->songRepository->findOneById($id);
    }

}

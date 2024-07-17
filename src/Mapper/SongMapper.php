<?php

namespace App\Mapper;

use App\Entity\Song;
use App\Model\ReduceSong;
use App\Model\SongDetails;

class SongMapper
{
    public function transformToReduceSong(Song $song): ReduceSong
    {
        return (new ReduceSong())
            ->setId($song->getId())
            ->setTitle($song->getTitle())
        ;
    }

    public function transformToSongDetails(Song $song): SongDetails
    {

        $songDetailsToUpdate = (new SongDetails())
            ->setId($song->getId())
            ->setTitle($song->getTitle())
            ->setCurrentSong($song->isCurrentSong())
        ;

        if($song->getDescription()){
            $songDetailsToUpdate->setDescription($song->getDescription());
        }

        if($song->getCategory()){
            $songDetailsToUpdate->setCategoryId($song->getCategory()->getId());
        }

        return $songDetailsToUpdate;
    }

}

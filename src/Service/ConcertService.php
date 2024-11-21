<?php

namespace App\Service;

use App\Entity\ConcertDay;
use App\Repository\ConcertDayRepository;
use App\Repository\ConcertVideoRepository;

class ConcertService
{
    public function __construct(
        private readonly ConcertDayRepository $concertDayRepository,
        private readonly ConcertVideoRepository $concertVideoRepository,
    ){

    }

    public function findConcertName(int $concertId)
    {
        return $this->concertDayRepository->findOneById($concertId)->getName();
    }

    public function findVideo(int $videoId)
    {
        return $this->concertVideoRepository->findOneById($videoId);
    }

}

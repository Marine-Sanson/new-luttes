<?php

namespace App\Mapper;

use App\Entity\Event;
use App\Model\EventForHome;
use App\Entity\EventCategory;
use App\Model\EventForAgenda;

class EventMapper
{

    public function transformToEventsForHome(Event $event): EventForHome
    {
        return (new EventForHome())
            ->setDate($event->getDate())
            ->setPublicDetails($event->getPublicDetails())
        ;
    }
    public function transformToEventForAgenda(Event $event, string $cat, int $oui, int $non, int $nsp, int $pasrep): EventForAgenda
    {
        $status = "privé";
        if ($event->getStatus() === false)
        {
            $status = "public";
        }

        return (new EventForAgenda())
            ->setId($event->getId())
            ->setDate($event->getDate())
            ->setEventCategory($cat)
            ->setPrivateDetails($event->getPrivateDetails())
            ->setStatus($status)
            ->setOui($oui)
            ->setNon($non)
            ->setNsp($nsp)
            ->setPasrep($pasrep)
            ;

    }

}

<?php

namespace App\Mapper;

use App\Entity\Event;
use App\Entity\EventCategory;
use App\Model\EventForAgenda;

class EventMapper
{
    public function transformToEventForAgenda(Event $event, string $cat, int $oui, int $non, int $nsp, int $pasrep): EventForAgenda
    {
        $status = "privé";
        if ($event->getStatus() === 0)
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

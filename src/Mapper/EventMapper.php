<?php

namespace App\Mapper;

use App\Entity\Event;
use App\Model\EventForHome;
use App\Model\EventToManage;
use App\Model\EventForAgenda;
use App\Model\EventForMembersHome;

class EventMapper
{

    public function transformToEventsForHome(Event $event): EventForHome
    {
        $publicDetails = null;
        if ($event->getPublicDetails()){
            $publicDetails = $event->getPublicDetails();
        }
        return (new EventForHome())
            ->setDate($event->getDate())
            ->setPublicDetails($publicDetails)
        ;
    }

    public function transformToEventToManage($event): EventToManage
    {

        return (new EventToManage())
            ->setId($event->getId())
            ->setDate($event->getDate())
            ->setEventCategory($event->getEventCategory())
            ->setPrivateDetails($event->getPrivateDetails())
            ->setPublicDetails($event->getPublicDetails())
            ->setStatus($event->getStatus())
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

    public function transformToEventForMembersHome(Event $event, int $participationId): EventForMembersHome
    {
        return (new EventForMembersHome())
            ->setEventId($event->getId())
            ->setParticipationId($participationId)
            ->setCategory($event->getEventCategory()->getName())
            ->setDate($event->getDate())
            ->setPrivateDetails($event->getPrivateDetails())
        ;
    }

}

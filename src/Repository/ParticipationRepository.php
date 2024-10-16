<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Participation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Participation>
 */
class ParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }

    public function saveParticipation($participation): void
    {
        $this->getEntityManager()->persist($participation);
        $this->getEntityManager()->flush();
    }

    public function countParticipation(int $eventId, int $id): int
    {
        return $this->createQueryBuilder('q')
                    ->select('count(q.id)')
                    ->where('q.status = :id')
                    ->andWhere('q.event = :eventId')
                    ->setParameter('id', $id)
                    ->setParameter('eventId', $eventId)
                    ->getQuery()
                    ->getSingleScalarResult()
                    ;
    }

    public function findParticipationByStatus(int $eventId, int $status): ?array
    {
       return $this->createQueryBuilder('p')
                   ->where('p.status = :status')
                   ->andWhere('p.event = :eventId')
                   ->setParameter('status', $status)
                   ->setParameter('eventId', $eventId)
                   ->getQuery()
                   ->getResult()
       ;
    }

    public function findByParticipationStatus($user, $status): ?array
    {
        return $this->createQueryBuilder('p')
            ->where('p.user = :user')
            ->andWhere('p.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByEvent(Event $event): ?array
    {
        return $this->createQueryBuilder('p')
            ->where('p.event = :event')
            ->setParameter('event', $event)
            ->getQuery()
            ->getResult()
        ;
    }

    public function deleteParticipation(Participation $participation): void
    {
        $this->getEntityManager()->remove($participation);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return Participation[] Returns an array of Participation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Participation
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

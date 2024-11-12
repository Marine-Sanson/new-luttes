<?php

namespace App\Repository;

use App\Entity\Song;
use App\Entity\SongCategory;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Song>
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

    public function saveSong(Song $song)
    {
        $this->getEntityManager()->persist($song);
        $this->getEntityManager()->flush();

        return $song;
    }

    public function findSongsWithoutCategory()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.category IS NULL')
            ->orderBy('s.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByCategoryId(int $categoryId)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.category = :category_id')
            ->setParameter('category_id', $categoryId)
            ->orderBy('s.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Song[] Returns an array of Song objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Song
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

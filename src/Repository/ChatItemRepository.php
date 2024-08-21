<?php

namespace App\Repository;

use DateTimeImmutable;
use App\Entity\ChatItem;
use App\Entity\ChatAnswer;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<ChatItem>
 */
class ChatItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatItem::class);
    }

    public function findAllChatItems(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function saveChatItem(ChatItem $chatItem)
    {
        $this->getEntityManager()->persist($chatItem);
        $this->getEntityManager()->flush();

    }
    
    public function findOldChatItems(DateTimeImmutable $oldDate): ?array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.createdAt < :oldDate')
            ->setParameter('oldDate', $oldDate)
            ->getQuery()
            ->getResult()
        ;
    }

    public function deleteChatItem(ChatItem $oldChatItem)
    {
        $this->getEntityManager()->remove($oldChatItem);
        $this->getEntityManager()->flush();
    }

    public function deleteChatAnswer(ChatAnswer $chatAnswer)
    {
        $this->getEntityManager()->remove($chatAnswer);
        $this->getEntityManager()->flush();
    }

    public function saveChatAnswer(ChatAnswer $chatAnswer)
    {
        $this->getEntityManager()->persist($chatAnswer);
        $this->getEntityManager()->flush();

    }

    //    /**
    //     * @return ChatItem[] Returns an array of ChatItem objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ChatItem
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

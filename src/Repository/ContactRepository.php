<?php

namespace App\Repository;

use DateTimeImmutable;
use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function saveContact($email, $object, $content, $date)
    {
        $contact = (new Contact())
            ->setMail($email)
            ->setObject($object)
            ->setContent($content)
            ->setCreatedAt(DateTimeImmutable::createFromMutable($date));

        $this->getEntityManager()->persist($contact);
        $this->getEntityManager()->flush();

        return true;
    }

    //    /**
    //     * @return Contact[] Returns an array of Contact objects
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

    //    public function findOneBySomeField($value): ?Contact
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

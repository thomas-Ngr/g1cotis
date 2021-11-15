<?php

namespace App\Repository;

use App\Entity\DispatchRecipient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DispatchRecipient|null find($id, $lockMode = null, $lockVersion = null)
 * @method DispatchRecipient|null findOneBy(array $criteria, array $orderBy = null)
 * @method DispatchRecipient[]    findAll()
 * @method DispatchRecipient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispatchRecipientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DispatchRecipient::class);
    }

    // /**
    //  * @return DispatchRecipient[] Returns an array of DispatchRecipient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DispatchRecipient
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

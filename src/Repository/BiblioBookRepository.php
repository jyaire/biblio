<?php

namespace App\Repository;

use App\Entity\BiblioBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BiblioBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method BiblioBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method BiblioBook[]    findAll()
 * @method BiblioBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiblioBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BiblioBook::class);
    }

    // /**
    //  * @return BiblioBook[] Returns an array of BiblioBook objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BiblioBook
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

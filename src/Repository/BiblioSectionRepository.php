<?php

namespace App\Repository;

use App\Entity\BiblioSection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BiblioSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method BiblioSection|null findOneBy(array $criteria, array $orderBy = null)
 * @method BiblioSection[]    findAll()
 * @method BiblioSection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiblioSectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BiblioSection::class);
    }

    // /**
    //  * @return BiblioSection[] Returns an array of BiblioSection objects
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
    public function findOneBySomeField($value): ?BiblioSection
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

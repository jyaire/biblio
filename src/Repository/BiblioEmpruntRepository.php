<?php

namespace App\Repository;

use App\Entity\BiblioEmprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BiblioEmprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method BiblioEmprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method BiblioEmprunt[]    findAll()
 * @method BiblioEmprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiblioEmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BiblioEmprunt::class);
    }

    // /**
    //  * @return BiblioEmprunt[] Returns an array of BiblioEmprunt objects
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
    public function findOneBySomeField($value): ?BiblioEmprunt
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

<?php

namespace App\Repository;

use App\Entity\BiblioUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method BiblioUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method BiblioUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method BiblioUser[]    findAll()
 * @method BiblioUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiblioUserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BiblioUser::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof BiblioUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return BiblioUser[] Returns an array of BiblioUser objects
     */
     public function findClassDistinct(){
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder
            ->select('u.section')
            ->where('u.section != :val1')
            ->andWhere('u.section != :val2')
            ->setParameter('val1', 'adulte')
            ->setParameter('val2', 'sorti')
            ->from($this->getClassName(), 'u')
            ->distinct(true);

        return $builder->getQuery()->getResult();
    }

    // methode pour trouver tous les utilisateurs, sauf les adultes et les anciens élèves trié par classe
     /**
      * @return BiblioUser[] Returns an array of BiblioUser objects
      */
    public function findAllButAdultsButLeft()
    {
        return $this->createQueryBuilder('u')
            ->where('u.section != :val1')
            ->andWhere('u.section != :val2')
            ->setParameter('val1', 'adulte')
            ->setParameter('val2', 'sorti')
            ->orderBy('u.section', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // methode pour trouver tous les utilisateurs, sauf les adultes et les anciens élèves trié par nom
    /**
     * @return BiblioUser[] Returns an array of BiblioUser objects
     */
    public function findAllButAdultsButLeftByName()
    {
        return $this->createQueryBuilder('u')
            ->where('u.section != :val1')
            ->andWhere('u.section != :val2')
            ->setParameter('val1', 'adulte')
            ->setParameter('val2', 'sorti')
            ->orderBy('u.nom', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?BiblioUser
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

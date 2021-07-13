<?php

namespace App\Repository;

use App\Entity\CommentRecette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentRecette|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentRecette|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentRecette[]    findAll()
 * @method CommentRecette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentRecette::class);
    }

    public function countCommentRecette()
    {
        $queryBuilder = $this->createQueryBuilder('k');
        $queryBuilder->select('Count(k.id) as value');

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    // /**
    //  * @return CommentRecette[] Returns an array of CommentRecette objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentRecette
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\CommentArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentArticle[]    findAll()
 * @method CommentArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentArticle::class);
    }

    public function countCommentArticle()
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('Count(c.id) as value');

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    // /**
    //  * @return CommentArticle[] Returns an array of CommentArticle objects
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
    public function findOneBySomeField($value): ?CommentArticle
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

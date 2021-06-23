<?php

namespace App\Repository;

use App\Entity\NewsFeed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewsFeed|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsFeed|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsFeed[]    findAll()
 * @method NewsFeed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsFeedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsFeed::class);
    }

    // /**
    //  * @return NewsFeed[] Returns an array of NewsFeed objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NewsFeed
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

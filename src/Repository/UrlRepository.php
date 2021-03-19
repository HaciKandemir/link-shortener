<?php

namespace App\Repository;

use App\Entity\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Url|null find($id, $lockMode = null, $lockVersion = null)
 * @method Url|null findOneBy(array $criteria, array $orderBy = null)
 * @method Url[]    findAll()
 * @method Url[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Url::class);
    }

    public function topShortenDomain(int $limit)
    {
        $query = $this->createQueryBuilder('u')
            ->select('u.url, COUNT(u.url) as count')
            ->groupBy('u.url')
            ->orderBy('count', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function topUser(int $limit)
    {
        $query = $this->createQueryBuilder('u')
            ->select('us.username, COUNT(us.id) as count')
            ->groupBy('us.id')
            ->orderBy('count', 'DESC')
            ->leftJoin("App:User", 'us', Join::WITH, 'u.user = us.id')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        return $query;
    }

    // /**
    //  * @return Url[] Returns an array of Url objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Url
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

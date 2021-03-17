<?php

namespace App\Repository;

use App\Entity\UrlStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UrlStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlStats[]    findAll()
 * @method UrlStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrlStats::class);
    }

    // /**
    //  * @return UrlStats[] Returns an array of UrlStats objects
    //  */

    public function topBrowser(array $urls)
    {
        $data = [];
        foreach ($urls as $url){
            $rows = $this->createQueryBuilder('u')
                ->select('u.browser')
                ->andWhere('u.url_id = :id')
                ->setParameter('id', $url)
                ->getQuery()
                ->getResult();
            if ($rows){
                foreach ($rows as $row){
                    $array = explode(' ', $row['browser']);
                    $array = explode('/',end($array));
                    $data[] = $array[0];
                }
            }
        }
        $data = array_count_values($data);
        arsort($data);
        return array_slice($data, 0, 5);
    }

    public function topDevice(array $urls)
    {
        $data = [];
        foreach ($urls as $url){
            $rows = $this->createQueryBuilder('u')
                ->select('u.device')
                ->andWhere('u.url_id = :id')
                ->setParameter('id', $url)
                ->getQuery()
                ->getResult();
            if ($rows){
                foreach ($rows as $row){
                    $data[] = $row['device'];
                }
            }
        }
        $data = array_count_values($data);
        arsort($data);
        return array_slice($data, 0, 5);
    }


    /*
    public function findOneBySomeField($value): ?UrlStats
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

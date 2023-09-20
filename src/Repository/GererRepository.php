<?php

namespace App\Repository;

use App\Entity\Gerer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gerer>
 *
 * @method Gerer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gerer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gerer[]    findAll()
 * @method Gerer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GererRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gerer::class);
    }

//    /**
//     * @return Gerer[] Returns an array of Gerer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Gerer
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

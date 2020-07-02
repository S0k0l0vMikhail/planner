<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Department|null find($id, $lockMode = null, $lockVersion = null)
 * @method Department|null findOneBy(array $criteria, array $orderBy = null)
 * @method Department[]    findAll()
 * @method Department[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

     /**
      * @return Department[] Returns an array of Department objects
      */
    public function findByOrganizationId($id)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.organizationId = :val')
            ->setParameter('val', $id)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Department
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

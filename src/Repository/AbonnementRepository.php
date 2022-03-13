<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    // /**
    //  * @return Abonnement[] Returns an array of Abonnement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Abonnement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function OrderByCout(){
        $em=$this->getEntityManager();
        $query=$em->createQuery('select s FROM App\Entity\Abonnement s ORDER BY s.cout');
        return $query->getResult();
    }
    public function OrderByCoutDSC(){
        $em=$this->getEntityManager();
        $query=$em->createQuery('select s FROM App\Entity\Abonnement s ORDER BY s.cout DESC');
        return $query->getResult();
    }
    public function findByNamePopular(string $search = null)
    {
        $queryBuilder = $this->createQueryBuilder('a')

            ->where('a.id LIKE :searchTerm')
            ->orWhere('a.nom LIKE :searchTerm')
            ->orWhere('a.description LIKE :searchTerm')
            ->orWhere('a.cout LIKE :searchTerm')

            ->setParameter('searchTerm', '%'.$search.'%');


        return $queryBuilder
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

}
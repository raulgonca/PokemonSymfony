<?php

namespace App\Repository;

use App\Entity\Pokedex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Pokedex>
 */
class PokedexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokedex::class);
    }

//    /**
//     * @return Pokedex[] Returns an array of Pokedex objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Pokedex
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findPokedexesByUser(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user') 
            ->setParameter('user', $user) 
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult(); 
    }
    

    //  Funcion Para obtener pokemons malheridos del Usuario logueado
    public function findAllInjuredByUser(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.status = :status')
            ->andWhere('p.user = :user')
            ->setParameter('status', 'malherido')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
    
}

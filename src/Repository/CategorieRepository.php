<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function add(Categorie $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Categorie $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne la liste des catégories des formations d'une playlist
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array{
        return $this->createQueryBuilder('c')
                ->join('c.formations', 'f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('c.name', 'ASC')   
                ->getQuery()
                ->getResult();        
    }  

    /**
     * Retourne toutes les catégories
     * @return Categorie[]
     */
    public function findAllCategories(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

/**
 * Retourne le nombre de formations associées à une catégorie
 * @param int $idCategorie
 * @return int
 */
public function countFormationsForOneCategorie(int $idCategorie): int
{
    return (int) $this->getEntityManager()->createQueryBuilder()
        ->select('COUNT(f.id)')
        ->from('App\Entity\Formation', 'f')
        ->join('f.categories', 'c')
        ->where('c.id = :id')
        ->setParameter('id', $idCategorie)
        ->getQuery()
        ->getSingleScalarResult();
}


public function findCategoriesOrderByNbFormationsDesc(): array
{
    return $this->createQueryBuilder('c')
        ->select('c', 'COUNT(f.id) AS nbFormations')
        ->leftJoin('c.formations', 'f')
        ->groupBy('c.id')
        ->orderBy('nbFormations', 'DESC')
        ->getQuery()
        ->getResult();
}


    
}

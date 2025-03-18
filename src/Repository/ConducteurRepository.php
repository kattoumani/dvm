<?php
namespace App\Repository;
 
use App\Entity\Conducteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
 
/**
 * Création d'un Repository pour la classe Conducteur
 */
class ConducteurRepository extends ServiceEntityRepository
{
    /**
     * Constructeur lié à la classe Conducteur
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conducteur::class);
    }
 
    /**
     * Ajouter ou modifier un enregistrement
     */
    public function save(Conducteur $conducteur, bool $flush = false): void
    {
        $this->getEntityManager()->persist($conducteur);
 
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
 
    /**
     * @return Conducteur[]
     * Retourner un tableau d'objets Conducteur classés par ordre alphabétique
     */
    public function findAllOrderedByName()
    {
        $r = $this->createQueryBuilder('c')
            ->orderBy('c.co_nom', 'ASC')
            ->getQuery()
            ->getResult();
        return $r;
    }
 
    // Ajoutez ici d'autres méthodes personnalisées si nécessaire
    //...
    
    /**
     * Recherche les conducteurs par nom
     */
    public function findBySearch(string $searchTerm)
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.co_nom LIKE :searchTerm')
        ->setParameter('searchTerm', '%' . $searchTerm . '%') // Recherche partielle
        ->getQuery()
        ->getResult();
    }
}
 
?>

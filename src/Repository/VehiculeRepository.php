<?php
namespace App\Repository;
 
use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
 
/**
 * @extends ServiceEntityRepository<Vehicule>
 */
class VehiculeRepository extends ServiceEntityRepository
{
    /**
     * Constructeur
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }
 
    /**
     * Sauvegarde dans la base de donnée
     */
    public function save(Vehicule $vehicule, bool $flush = false): void
    {
        $this->getEntityManager()->persist($vehicule);
 
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
 
    /**
     * @return Vehicule[]
     *
     * Retourne un tableau de Vehicule classés suivant la marque ($ve_Marque)
     * puis le modèle ($ve_modele)
     */
    public function findAllOrdered()
    {
        $r = $this->createQueryBuilder('c')
            ->orderBy('c.ve_marque', 'ASC')
            ->addOrderBy('c.ve_modele', 'ASC')
            ->getQuery()
            ->getResult();
        return $r;
    }
}
 
?>
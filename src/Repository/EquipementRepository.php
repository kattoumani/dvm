<?php
namespace App\Repository;
 
use App\Entity\Equipement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
 
/**
 * @extends ServiceEntityRepository<Equipement>
 */
class EquipementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipement::class);
    }
 
    public function save(Equipement $equipement, bool $flush = false): void
    {
        $this->getEntityManager()->persist($equipement);
 
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
 
    /**
     * @return Equipement[] Returns an array of Equipement objects
     * ordered by their label ($eq_Libelle)
     */
    public function findAllOrderedByName()
    {
        $r = $this->createQueryBuilder('c')
            ->orderBy('c.eq_libelle', 'ASC')
            ->getQuery()
            ->getResult();
        return $r;
    }
}
 
?>
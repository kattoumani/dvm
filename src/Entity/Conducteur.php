<?php
// Définition de l'espace de nom
namespace App\Entity;
 
// Import des librairies
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ConducteurRepository;
 
/**
 * Définition de la classe Conducteur liée à son Repository.
 * Un conducteur est défini par
 * - son identifiant entier co_id
 * - son nom (chaîne de 30 caractères) co_nom
 */
#[ORM\Entity(repositoryClass: ConducteurRepository::class)]
class Conducteur
{
    // --------------------------------------------------------------
    // Description des champs
    // --------------------------------------------------------------
 
    #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column(type: 'integer')]
   private ?int $co_id = null;
 
    #[ORM\Column(type:'string', length:30)]
   private ?string $co_nom = null;
   
    // --------------------------------------------------------------
    // Methodes
    // --------------------------------------------------------------
 
    /**
     * Constructeur
     * Ici le constructeur est vide, il n'y a pas d'initialisation mais
     * on pourrait initialiser les deux champs co_id, co_nom
     */
    public function __construct()
    {
       
    }
 
    /**
     * Obtenir la valeur du champ identifiant (co_id)
     */
    function getCoId() {
 
        return $this->co_id;
 
    }
 
    /**
     * Obtenir la valeur du champ nom (co_nom)
     */
    function getCoNom() {
 
        return $this->co_nom;
 
    }
 
    /**
     * Modifier la valeur du champ nom (co_nom)
     */
    function setCoNom($nom) {
 
        $this->co_nom = $nom;
   
        return $this;
   
    }
 
}
 
?>

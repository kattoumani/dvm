<?php
namespace App\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
 
use App\Entity\Equipement;
use App\Entity\Vehicule;
use App\Repository\EquipementVehiculeRepository;
 
/**
 * Classe qui fait le lien entre véhicules et équipements
 */
#[ORM\Entity(repositoryClass: EquipementVehiculeRepository::class)]
class EquipementVehicule
{
    // identifiant
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'eqve_id', type: 'integer')]
    private ?int $eqve_id = null;
    
    // équipement (ex: pneu neige, 100€)
    #[ORM\ManyToOne(targetEntity: Equipement::class, inversedBy: "eq_equipement_vehicule")]
    #[ORM\JoinColumn(nullable: false, name: "eqve_equipement_id", referencedColumnName: "eq_id")]
    private ?Equipement $eqve_equipement = null;
    
    // véhicule (ex: Volkswagen, Touran)
    #[ORM\ManyToOne(targetEntity: Vehicule::class, inversedBy: "ve_equipement_vehicule")]
    #[ORM\JoinColumn(nullable: false, name: "eqve_vehicule_id", referencedColumnName: "ve_id")]
    private ?Vehicule $eqve_vehicule = null;
    
    // nombre d'équipements dans le véhicule (ex: 4)
    #[ORM\Column(name: "eqve_quantite", type: "integer")]
    private ?int $eqve_quantite = null;
 
    function __construct() {
        
    }
 
    function getEqVeId() {
        return $this->eqve_id;
    }
 
    function getEqVeQuantite() {
        return $this->eqve_quantite;
    }
 
    function setEqVeQuantite($quantite) {
        $this->eqve_quantite = $quantite;
        return $this;
    }
 
    function getEqVeEquipement() : ?Equipement{
        return $this->eqve_equipement;
    }
 
    function setEqVeEquipement($equipement) {
        $this->eqve_equipement = $equipement;
        return $this;
    }
 
    function getEqVeVehicule() : ?Vehicule {
        return $this->eqve_vehicule;
    }
 
    function setEqVeVehicule($vehicule) {
        $this->eqve_vehicule = $vehicule;
        return $this;
    }
 
}
 
?>
<?php
namespace App\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
 
use App\Repository\EquipementRepository;
use App\Entity\EquipementVehicule;
 
 
#[ORM\Entity(repositoryClass: EquipementRepository::class)]
class Equipement
{
    // --------------------------------------------------------------
    // Description des champs
    // --------------------------------------------------------------
 
    #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column(type: 'integer')]
   private ?int $eq_id = null;
 
    #[ORM\Column(type:'string', length:30)]
   private ?string $eq_libelle = null;
 
    #[ORM\Column(type:'float')]
   private ?float $eq_prix = null;
 
   private Collection $eq_equipement_vehicule;

    // --------------------------------------------------------------
    // Methodes
    // --------------------------------------------------------------
 
    public function __construct()
    {
        $this->eq_equipement_vehicule = new ArrayCollection();
    }
 
    function getEqId() {
 
        return $this->eq_id;
 
    }
 
    function getEqLibelle() {
 
        return $this->eq_libelle;
 
    }
 
    function setEqLibelle($libelle) {
 
        $this->eq_libelle = $libelle;
   
        return $this;
   
    }
 
    function getEqPrix() {
 
        return $this->eq_prix;
 
    }
 
    function setEqPrix($prix) {
 
        $this->eq_prix = $prix;
   
        return $this;
   
    }

    // retourne les liens EquipementVehicule
    public function getEqEquipementVehicule(): Collection
    {
        return $this->eq_equipement_vehicule;
    }

    // ajoute un EquipementVehicule
    public function addEqEquipementVehicule(EquipementVehicule $equipement_vehicule): static
    {
        if (!$this->eq_equipement_vehicule->contains($equipement_vehicule)) {
            $this->eq_equipement_vehicule[] = $equipement_vehicule;
            $equipement_vehicule->setEqVeEquipement($this);
        }
 
        return $this;
    }

    // supprimer un EquipementVehicule
    public function removeEqEquipementVehicule(EquipementVehicule $equipement_vehicule): static
    {
        if ($this->eq_equipement_vehicule->removeElement($equipement_vehicule)) {
            // set the owning side to null (unless already changed)
            if ($equipement_vehicule->getEqVeEquipement() === $this) {
                $equipement_vehicule->setEqVeEquipement(null);
            }
        }
 
        return $this;
    }


 
}
 
?>
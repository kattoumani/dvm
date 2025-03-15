<?php
namespace App\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
 
// on a besoin de conducteur car un véhicule est associé
// à un conducteur
use App\Entity\Conducteur;
 
use App\Repository\VehiculeRepository;

use App\Entity\EquipementVehicule;
 
// for date
use Symfony\Component\Validator\Constraints as Assert;
 
 
#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    // --------------------------------------------------------------
    // Description des champs
    // --------------------------------------------------------------
 
    #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column(type: 'integer')]
   private ?int $ve_id = null;
 
    #[ORM\Column(type:'string', length:30)]
   private ?string $ve_marque = null;
 
    #[ORM\Column(type:'string', length:30)]
   private ?string $ve_modele = null;
 
    #[ORM\Column(type: "datetime", nullable: true)]
   #[Assert\NotBlank]
   #[Assert\NotNull]
   private  $ve_date;
 
    // relation vers Conducteur : un véhicule possède un seul conducteur
    #[ORM\ManyToOne(targetEntity : Conducteur::class, inversedBy: "vehicules")]
   #[ORM\JoinColumn(nullable:false, name: 've_co_id', referencedColumnName: 'co_id')]
   private Conducteur $ve_conducteur;

   private Collection $ve_equipement_vehicule;
 
    // --------------------------------------------------------------
    // Methodes
    // --------------------------------------------------------------
 
    public function __construct()
    {
        $this->ve_date = new \DateTime();
        $this->ve_equipement_vehicule = new ArrayCollection();
    }
 
    function getVeId() {
 
        return $this->ve_id;
 
    }
 
    function getVeMarque() {
 
        return $this->ve_marque;
 
    }
 
    function setVeMarque($marque) {
 
        $this->ve_marque = $marque;
   
        return $this;
   
    }
 
    function getVeModele() {
 
        return $this->ve_modele;
 
    }
 
    function setVeModele($modele) {
 
        $this->ve_modele = $modele;
   
        return $this;
   
    }
 
    public function getVeDate(): ?\DateTimeInterface
    {
        return $this->ve_date;
    }
 
    public function setVeDate(\DateTimeInterface $date): self
    {
        $this->ve_date = $date;
        return $this;
    }
 
    public function getVeConducteur(): ?Conducteur
    {
        return $this->ve_conducteur;
    }
 
    public function setVeConducteur(?Conducteur $conducteur): self
    {
        $this->ve_conducteur = $conducteur;
 
        return $this;
    }

    // retourne les équipements associés au véhicule
    public function getVeEquipementVehicule(): Collection
    {
        return $this->ve_equipement_vehicule;
    }

    public function addVeEquipementVehicule(EquipementVehicule $equipement_vehicule): static
    {
        if (!$this->ve_equipement_vehicule->contains($equipement_vehicule)) {
            $this->ve_equipement_vehicule[] = $equipement_vehicule;
            $equipement_vehicule->setEqVeVehicule($this);
        }
 
        return $this;
    }

    // supprime les équipements associés au véhicule
    public function removeVeEquipementVehicule(EquipementVehicule $equipement_vehicule): static
    {
        if ($this->ve_equipement_vehicule->removeElement($equipement_vehicule)) {
            // set the owning side to null (unless already changed)
            if ($equipement_vehicule->getEqVeVehicule() === $this) {
                $equipement_vehicule->setEqVeVehicule(null);
            }
        }
 
        return $this;
    }
 
}
 
?>
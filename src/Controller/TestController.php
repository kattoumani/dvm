<?php
// src/Controller/ConducteurController.php
namespace App\Controller;
 
use App\Entity\Conducteur;
use App\Entity\Vehicule;
use App\Entity\Equipement;
use App\Entity\EquipementVehicule;
 
use App\Repository\ConducteurRepository;
use App\Repository\VehiculeRepository;
use App\Repository\EquipementRepository;
use App\Repository\EquipementVehiculeRepository;
 
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
 
class TestController extends AbstractController
{
 
 
    /**
     * Insertion :
     * - d'un conducteur nommé Donald
     * - d'un véhicule : Audi, Q5, acheté le 30/09/1970 et associé à Donald
     * - création d'équipements :
     *    - Jantes 17 pouces, 356 €
     *    - insert bois, 200 €
     * - ajout de 4 jantes au véhicule Audi de Donald ainsi que l'insert bois
     *
     * Au final on redirige vers le template test.html.twig afin d'afficher
     * le résultat
     */
   
    #[Route('/test/inserer', name: 'test_inserer')]
   public function inserer(Request $request, EntityManagerInterface $entityManager): Response
    {
        // création d'un conducteur et renseignement des champs
        $conducteur = new Conducteur();
        $conducteur->SetCoNom("Donald");
 
        // création d'un véhicule et renseignement des champs
        $vehicule = new Vehicule();
        $vehicule->setVeMarque('Audi');
        $vehicule->setVeModele('Q5');
        $vehicule->setVeDate(new \DateTime('1970-09-30'));
 
        // associer le conducteur au véhicule
        $vehicule->setVeConducteur($conducteur);
 
        // enregistrement conducteur
        $entityManager->persist($conducteur);
       
        // créations des équipements
        $equipement_1 = new Equipement();
        $equipement_1->setEqLibelle('Jantes 17 pouces');
        $equipement_1->setEqPrix(356);
 
        $equipement_2 = new Equipement();
        $equipement_2->setEqLibelle('Insert bois');
        $equipement_2->setEqPrix(200);
 
        // création d'un association entre équipement 1 et véhicule
        $equip_vehi_1 = new EquipementVehicule();
        $equip_vehi_1->setEqVeVehicule($vehicule);
        $equip_vehi_1->setEqVeEquipement($equipement_1);
        $equip_vehi_1->setEqVeQuantite(4);
 
        // création d'un association entre équipement 2 et véhicule
        $equip_vehi_2 = new EquipementVehicule();
        $equip_vehi_2->setEqVeVehicule($vehicule);
        $equip_vehi_2->setEqVeEquipement($equipement_2);
        $equip_vehi_2->setEqVeQuantite(1);
       
        // mise en relation équipements et véhicules
        $vehicule->addVeEquipementVehicule($equip_vehi_1);
        $vehicule->addVeEquipementVehicule($equip_vehi_2);
       
        $equipement_1->addEqEquipementVehicule($equip_vehi_1);
        $equipement_2->addEqEquipementVehicule($equip_vehi_2);
 
        // enregistrement des équipements
        $entityManager->persist($equipement_1);
        $entityManager->persist($equipement_2);
 
        // enregistrement des associations équipements/véhicule
        $entityManager->persist($equip_vehi_1);
        $entityManager->persist($equip_vehi_2);
       
        // enregistrement du véhicule
        $entityManager->persist($vehicule);
        $entityManager->flush();
 
 
        $repo_conducteur = $entityManager->getRepository(Conducteur::class);
        $repo_vehicule = $entityManager->getRepository(Vehicule::class);
        $repo_equipement = $entityManager->getRepository(Equipement::class);
 
        $liste_conducteur = $repo_conducteur->findAll();
        $liste_vehicule = $repo_vehicule->findAll();
        $liste_equipement = $repo_equipement->findAll();
       
 
        return $this->render('test.html.twig', [
            'liste_conducteur' => $liste_conducteur,
            'liste_vehicule' => $liste_vehicule,
            'liste_equipement' => $liste_equipement
        ]);
    }
 
 
    /**
     * Affichage du contenu de la base
     */
    #[Route('/test/lister', name: 'test_lister')]
   public function route2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repo_conducteur = $entityManager->getRepository(Conducteur::class);
        $repo_vehicule = $entityManager->getRepository(Vehicule::class);
        $repo_equipement = $entityManager->getRepository(Equipement::class);
 
        $liste_conducteur = $repo_conducteur->findAll();
        $liste_vehicule = $repo_vehicule->findAll();
        $liste_equipement = $repo_equipement->findAll();
       
        return $this->render('test.html.twig', [
            'liste_conducteur' => $liste_conducteur,
            'liste_vehicule' => $liste_vehicule,
            'liste_equipement' => $liste_equipement
        ]);
    }
 
    // Ajoutez d'autres méthodes pour afficher, modifier, supprimer les conducteurs, etc.
}
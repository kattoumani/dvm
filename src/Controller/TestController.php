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
        
        $c2 = new Conducteur();
        $c2->setCoNom("Gilbert");
        
        $c3 = new Conducteur();
        $c3->setCoNom("Robert");
        
        // création d'un véhicule et renseignement des champs
        $vehicule = new Vehicule();
        $vehicule->setVeMarque('Audi');
        $vehicule->setVeModele('Q5');
        $vehicule->setVeDate(new \DateTime('1970-09-30'));
        
        // Vehicule 2
        $v2 = new Vehicule();
        $v2->setVeMarque('Renault');
        $v2->setVeModele('Rafale');
        $v2->setVeDate(new \DateTime('2025-01-14'));
        
        // Vehicule 3 
        $v3 = new Vehicule();
        $v3->setVeMarque('Volkswagen');
        $v3->setVeModele('Tiguan');
        $v3->setVeDate(new \DateTime(),'2021-09-05');
 
        // associer le conducteur au véhicule
        $vehicule->setVeConducteur($conducteur);
        $v2->setVeConducteur($c2);
        $v3->setVeConducteur($c3);
 
        // enregistrement conducteur
        $entityManager->persist($conducteur);
        $entityManager->persist($c2);
        $entityManager->persist($c3);
       
        // créations des équipements
        $equipement_1 = new Equipement();
        $equipement_1->setEqLibelle('Jantes 17 pouces');
        $equipement_1->setEqPrix(356);
 
        $equipement_2 = new Equipement();
        $equipement_2->setEqLibelle('Insert bois');
        $equipement_2->setEqPrix(200);
        
        // Equipement 3
        $eq3 = new Equipement();
        $eq3->setEqLibelle('Tapis de sol');
        $eq3->setEqPrix(80);
        
        // Equipemement 4
        $eq4 = new Equipement();
        $eq4->setEqLibelle('Câble de recharge');
        $eq4->setEqPrix(35);
 
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
        
        // Equipement 3
        $eq_ve3 = new EquipementVehicule();
        $eq_ve3->setEqVeVehicule($v2);
        $eq_ve3->setEqVeEquipement($eq3);
        $eq_ve3->setEqVeQuantite(4);
        
        // Equipement 4
        $eq_ve4 = new EquipementVehicule();
        $eq_ve4->setEqVeVehicule($v3);
        $eq_ve4->setEqVeEquipement($eq4);
        $eq_ve4->setEqVeQuantite(1);
       
        // mise en relation équipements et véhicules
        $vehicule->addVeEquipementVehicule($equip_vehi_1);
        $vehicule->addVeEquipementVehicule($equip_vehi_2);
        $v2->addVeEquipementVehicule($eq_ve3);
        $v3->addVeEquipementVehicule($eq_ve4);
        
       
        $equipement_1->addEqEquipementVehicule($equip_vehi_1);
        $equipement_2->addEqEquipementVehicule($equip_vehi_2);
        $eq3->addEqEquipementVehicule($eq_ve3);
        $eq4->addEqEquipementVehicule($eq_ve4);
 
        // enregistrement des équipements
        $entityManager->persist($equipement_1);
        $entityManager->persist($equipement_2);
        $entityManager->persist($eq3);
        $entityManager->persist($eq4);
        
        // enregistrement des associations équipements/véhicule
        $entityManager->persist($equip_vehi_1);
        $entityManager->persist($equip_vehi_2);
        $entityManager->persist($eq_ve3);
        $entityManager->persist($eq_ve4);
       
        // enregistrement du véhicule
        $entityManager->persist($vehicule);
        $entityManager->persist($v2);
        $entityManager->persist($v3);
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
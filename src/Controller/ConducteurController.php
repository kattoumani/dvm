<?php
// Fichier: src/Controller/ConducteurController.php
 
namespace App\Controller;
 
use App\Entity\Conducteur;
Use App\Form\ConducteurType;

use App\Entity\Vehicule;
use App\Entity\EquipementVehicule;
 
use App\Repository\ConducteurRepository;
use App\Repository\VehiculeRepository;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
 
// Utilisation d'un logger pour le débogage
use Psr\Log\LoggerInterface;
 
 
class ConducteurController extends AbstractController
{
    // Logger
    private $logger;
    private $entity_manager;
    private $repository;
 
    /**
     * Constructeur auquel on passe en paramètre un logger
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entity_manager)
    {
        $this->logger = $logger;
        $this->entity_manager = $entity_manager;
        // obtenir le Repository lié au conducteur depuis l'EntityManager
        $this->repository = $entity_manager->getRepository(Conducteur::class);
    }

    /**
     * Supprimer un conducteur étant donné son id
     */
    #[Route('/conducteur/supprimer/{id}', name: 'conducteur_supprimer')]
    public function supprimer($id): Response
    {
        // à partir du Repository, obtenir le conducteur grâce à son identifiant
        $conducteur = $this->repository->find($id);
                        
        if (!$conducteur) {
            // dans le cas où le conducteur n'aurait pas été trouvé, générer une exception
            throw $this->createNotFoundException('Aucun conducteur d\'identifiant ' . $id . ' n\'a été trouvé');
        }
        
        $vehicule = $this->entity_manager->getRepository(Vehicule::class)->findBy(['ve_conducteur' => $conducteur]);
        foreach($vehicule as $v){
            $equipementVehicule = $this->entity_manager->getRepository(EquipementVehicule::class)->findBy(['eqve_vehicule' => $v]);
            
            foreach ($equipementVehicule as $eq) {
                $this->entity_manager->remove($eq);
            }
            
            $this->entity_manager->remove($v);
        }
        
        // Récupération des véhicules et modifier a NULL
        /*if($vehicule){
            foreach($vehicule as $v){
                $v->setVeConducteur(null);
                $this->entity_manager->persist($v);
            }
        }*/
        
        $this->entity_manager->remove($conducteur);
        $this->entity_manager->flush();
        
       
        
       
        // Suppression du conducteur
        
 
        // se rediriger vers l'affichage de la liste des conducteurs
        // Attention on utilise le nom de la route 'conducteur_lister'
        // et non 'conducteur/lister'
        return $this->redirectToRoute('conducteur_lister');
 
    }

    //...
 
    /**
     * Afficher les conducteurs dans un tableau
     */

    #[Route('/conducteur/lister', name: 'conducteur_lister')]
     public function lister(Request $request): Response
    {
        // obtenir la liste de tous les conducteurs triés par ordre alphabétique
        // croissant
        $liste_conducteurs = $this->repository->findAllOrderedByName();
       
        return $this->render("conducteur/lister.html.twig", [
            'liste_conducteurs' => $liste_conducteurs
        ]);
    }

    //...
 
   /**
     * Créer un nouveau conducteur en affichant un formulaire
     * de saisie des informations
     */

     #[Route('/conducteur/ajouter', name: 'conducteur_creer')]
     public function ajouter(Request $request): Response
      {
          $this->logger->info( 'Ajouter un conducteur' );                
         
          $message_erreur = "";
   
          // créer un conducteur dont les champs sont vides
          $conducteur = new Conducteur();
   
          // créer un formulaire qui prend en compte les données du conducteur
          $form = $this->createForm(ConducteurType::class, $conducteur);
   
          // récupération des données de la requête, notamment des
          // informations liées à la saisie d'un conducteur
          $form->handleRequest($request);
         
          // Si on vient de soumettre le formulaire et que les données
          // sont valides
          if ($form->isSubmitted() && $form->isValid()) {
   
              // Check if a driver with the same name already exists
              $conducteur_existant = $this->repository->findOneBy(['co_nom' => $conducteur->getCoNom()]);
             
              if ($conducteur_existant) {
                
                  $message_erreur = 'Il existe déjà un conducteur de même nom';
         
              } else {
                 
                  // alors sauvegarder le conducteur (persist, flush)
                  $this->repository->save($conducteur, true);
   
                  // se rediriger vers l'affichage de la liste des conducteurs
                  // Attnetion on utilise le nom de la route 'conducteur_lister'
                  // et non 'conducteur/lister'
                  return $this->redirectToRoute('conducteur_lister');
              }
          }
   
              // sinon afficher la page contenant le formulaire d'ajout
          if (!empty($message_erreur)) $this->addFlash('error', $message_erreur);
             
          return $this->render('conducteur/ajouter.html.twig', [
              'form' => $form->createView(),
              'message_erreur' => $message_erreur
          ]);
      }

      /**
     * Modifier un conducteur étant donné son id
     */
    #[Route('/conducteur/modifier/{id}', name: 'conducteur_modifier')]
    public function modifier(Request $request, EntityManagerInterface $entityManager, int $id): Response
     {
  
         // à partir du Repository, obtenir le conducteur grâce à son identifiant
         $conducteur = $this->repository->find($id);
        
         // dans le cas où le conducteur n'aurait pas été trouvé, générer une exception
         if (!$conducteur) {
             throw $this->createNotFoundException('Acucun conducteur d\'identifiant ' . $id . ' n\'a été trouvé');
         }
  
         // créer le formulaire lié au conducteur
         $form = $this->createForm(ConducteurType::class, $conducteur);
  
         // récupération des données de la requête, notamment des
         // informations liées à la saisie d'un conducteur
         $form->handleRequest($request);
  
         // Si on vient de soumettre le formulaire et que les données
         // sont valies
         if ($form->isSubmitted() && $form->isValid()) {
  
             // alors sauvegarder le conducteur (persist, flush)
             $this->repository->save($conducteur, true);
  
             // se rediriger vers l'affichage de la liste des conducteurs
             // Attnetion on utilise le nom de la route 'conducteur_lister'
             // et non 'conducteur/lister'
             return $this->redirectToRoute('conducteur_lister');
         }
  
         // sinon afficher la page contenant le formulaire de modification
         return $this->render('conducteur/modifier.html.twig', [
             'form' => $form->createView(),
         ]);
  
     }

      #[Route('/conducteur/supprimer_tout', name: 'conducteur_tout_supprimer')]
      public function supprimerTout(): Response
      {
        
        
        
        /*foreach($conducteurs as $conducteur){
            $this->entity_manager->remove($conducteur);
            $vehicule = $this->entity_manager->getRepository(Vehicule::class)->findBy(['ve_conducteur' => $conducteur->getCoId()]);
            if($vehicule){
                foreach($vehicule as $v){
                    $v->setVeConducteur(null);
                    $this->entity_manager->persist($v);
                }
            }
        }*/
        
        $conducteurs = $this->repository->findAll();
          
        foreach ($conducteurs as $c){
            $this->entity_manager->remove($c);
            
            $vehicules = $this->entity_manager->getRepository(Vehicule::class)->findAll();
            foreach($vehicules as $v){
                $equipementVehicules = $this->entity_manager->getRepository(EquipementVehicule::class)->findAll();
                
                foreach ($equipementVehicules as $eq) {
                    $this->entity_manager->remove($eq);
                }
                
                $this->entity_manager->remove($v);
            }
            
        }
        
        
        
        
        
        
        
        
        
        
        $this->entity_manager->flush();
        
        return $this->redirectToRoute('conducteur_lister');

      }

      #[Route('/conducteur/liste_vehicules/{id}', name: 'conducteur_vehicules')]
      public function conducteur_vehicules(Request $request, EntityManagerInterface $entityManager, int $id): Response
      {
        $conducteur = $this->repository->find($id);
        $liste_vehicules = $entityManager->getRepository(Vehicule::class)->findBy(['ve_conducteur' => $id]);
        $total_eq = 0;
    
        foreach($liste_vehicules as $v){            
            $equipement_vehicules = $entityManager->getRepository(EquipementVehicule::class)->findBy(['eqve_vehicule' => $v->getVeId()]);
            foreach($equipement_vehicules as $eq){
                $total_eq += $eq->getEqVeEquipement()->getEqPrix();
            }
        }
        

        return $this->render('conducteur/vehicules.html.twig', [
            'conducteur' => $conducteur,
            'vehicules' => $liste_vehicules,
            'total_equipements' => $total_eq,
        ]);
        
      }

      #[Route('/vehicule/liste_equipements/{id}', name: 'vehicule_liste_equipements')]
      public function vehicule_liste_equipements(Request $request, EntityManagerInterface $entityManager, int $id): Response
      {
        $vehicule = $entityManager->getRepository(Vehicule::class)->find($id);
        $equipement_vehicule = $entityManager->getRepository(EquipementVehicule::class)->findBy(['eqve_vehicule' => $id]);
        $marque_vehicule = $vehicule->getVeMarque();
        $modele_vehicule = $vehicule->getVeModele();
        $nom_conducteur = $vehicule->getVeConducteur()->getCoNom();
        
        return $this->render('conducteur/vehicule_liste_equipements.html.twig', [
            'liste_equipements' => $equipement_vehicule,
            'marque_vehicule' => $marque_vehicule,
            'modele_vehicule' => $modele_vehicule,
            'nom_conducteur' => $nom_conducteur,
            'vehiculeId' => $vehicule->getVeId(),
        ]);
        #dump($equipement_vehicule);
      }
      
      #[Route('/conducteur/liste_conducteurs', name: 'liste_conducteurs')]
      public function liste_conducteurs(Request $request): Response {
          $les_conducteurs = $this->repository->findBy([], ['co_nom' => 'ASC']);
          dump($les_conducteurs);
          
          $data = [];
          
          foreach($les_conducteurs as $c){
              $les_vehicules = $this->entity_manager->getRepository(Vehicule::class)->findBy(['ve_conducteur' => $c], ['ve_date' => 'ASC']);
                
              $vehiculeData = [];
              foreach ($les_vehicules as $v){
                  $les_equipements = $this->entity_manager->getRepository(EquipementVehicule::class)->findBy(['eqve_vehicule' => $v]);  
             
                  $vehiculeData[] = [
                    'vehicule' => $v,
                    'equipements' => $les_equipements
                  ]; 
              }
            
            $data[] = [
                'conducteur' => $c,
                'vehicules' => $vehiculeData
            ];
          }
                  
          return $this->render('conducteur/tableau_conducteurs.html.twig', [
              'data' => $data
          ]);
      }
    
}

    



 
?>

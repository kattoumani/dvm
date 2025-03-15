<?php
// src/Controller/EquipementController.php
namespace App\Controller;
 
use App\Entity\Equipement;
use App\Entity\Vehicule;
use App\Entity\EquipementVehicule;
use App\Form\EquipementType;
use App\Form\VehiculeType;
use App\Form\EquipementVehiculeType;
use App\Repository\EquipementRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
 
// Utilisation d'un logger pour le débogage
use Psr\Log\LoggerInterface;
 
class EquipementController extends AbstractController
{
 
    // Logger
    private $logger;
    private $entity_manager;
    private $repository;
   
    /**
     * Constructeur
     */
    public function __construct(LoggerInterface $logger,
        EntityManagerInterface $entity_manager)
    {
        $this->logger = $logger;
        $this->entity_manager = $entity_manager;
        // obtenir le Repository lié au conducteur depuis l'EntityManager
        $this->repository = $entity_manager->getRepository(Equipement::class);
    }

    #[Route('/equipement/lister', name: 'equipement_lister')]
    public function lister(Request $request): Response
        {
            $liste_equipements = $this->repository->findAllOrderedByName();
    
            return $this->render("equipement/lister.html.twig", [
                'liste_equipements' => $liste_equipements
            ]);
        }

    /**
     * Créer un nouvel équipement en affichant un formulaire
     * de saisie des informations
     */
    #[Route('/equipement/ajouter', name: 'equipement_ajouter')]
    public function ajouter(Request $request): Response
    {
        $equipementVehicule = new EquipementVehicule();
            
        $form = $this->createFormBuilder($equipementVehicule)
            ->add('eqVeEquipement', EquipementType::class, [
                'label' => 'Équipement'
            ]) 
            ->add('eqVeVehicule', VehiculeType::class, [
                'label' => 'Voiture'
            ])
            ->add('eqve_quantite', IntegerType::class, [
                'label' => 'Quantité',
                'required' => true
            ])
            ->getForm();
       
        $form = $this->createForm(EquipementVehiculeType::class, $equipementVehicule);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
                
            $entityManager->persist($equipementVehicule);
            $entityManager->flush();

            return $this->redirectToRoute('equipement_lister');
        }
    
        return $this->render('equipement/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
    * Modifier un équipement étant donné son id
   */
  #[Route('/equipement/modifier/{id}', name: 'equipement_modifier')]
  public function modifier(Request $request, int $id): Response
    {
         $equipement = $this->repository->find($id);
       
        if (!$equipement) {
            throw $this->createNotFoundException('Aucun équipement avec l\'identifiant ' . $id . ' n\'a été trouvé');
        }
 
        $form = $this->createForm(EquipementType::class, $equipement);
 
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
           
            $this->repository->save($equipement, true);
 
            return $this->redirectToRoute('equipement_lister');
        }
 
        return $this->render('equipement/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
 
    }

    /**
     * Supprimer un équipement étant donné son id
     */
    #[Route('/equipement/supprimer/{id}', name: 'equipement_supprimer')]
    public function supprimer($id, EntityManagerInterface $entityManager): Response
    {
        // à partir du Repository, obtenir le conducteur grâce à son identifiant
        $equipement = $this->repository->find($id);
        $equipement_vehicule = $entityManager->getRepository(EquipementVehicule::class)->findBy(['eqve_equipement' => $id]);
       
        // dans le cas où le conducteur n'aurait pas été trouvé, générer une exception
        if (!$equipement) {
            throw $this->createNotFoundException('Aucun équipement d\'identifiant ' . $id . ' n\'a été trouvé');
        }
       
        // Suppression du conducteur
        $this->entity_manager->remove($equipement);
        $this->entity_manager->remove($equipement_vehicule[0]);
        $this->entity_manager->flush();
 
        // se rediriger vers l'affichage de la liste des conducteurs
        // Attention on utilise le nom de la route 'conducteur_lister'
        // et non 'conducteur/lister'
        return $this->redirectToRoute('equipement_lister');
 
    }

    #[Route('/equipement/ajouter/{id}', name: 'equipement_vehicule_ajouter')]
    public function equipement_vehicule_ajouter(Request $request,EntityManagerInterface $entityManager ,int $id)
    {
        $vehicule = $entityManager->getRepository(Vehicule::class)->find($id);

        if (!$vehicule) {
            throw $this->createNotFoundException('Véhicule non trouvé');
        }

        $equipementVehicule = new EquipementVehicule();
        $equipementVehicule->setEqVeVehicule($vehicule);
        $form = $this->createForm(EquipementVehiculeType::class, $equipementVehicule);

        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($equipementVehicule->getEqVeEquipement());
            $entityManager->persist($equipementVehicule);
            $entityManager->flush();

            return $this->redirectToRoute('equipement_lister');
        }
    
        return $this->render('equipement/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
        
   
}
 
?>
<?php
 
namespace App\Controller;
 
use App\Entity\EquipementVehicule;
use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use App\Repository\ConducteurRepository;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Form\FormTypeInterface;
 
// Utilisation d'un logger pour le débogage
use Psr\Log\LoggerInterface;
 
class VehiculeController extends AbstractController
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
        // obtenir le Repository lié au véhicule depuis l'EntityManager
        $this->repository = $entity_manager->getRepository(Vehicule::class);
    }

    #[Route('/vehicule/lister', name: 'vehicule_lister')]
    public function lister(Request $request): Response
        {
            $liste_vehicules = $this->repository->findAllOrdered();
    
            return $this->render("vehicule/lister.html.twig", [
                'liste_vehicules' => $liste_vehicules
            ]);
        }

    /**
     * Supprimer un véhicule étant donné son id
     */
    #[Route('/vehicule/supprimer/{id}', name: 'vehicule_supprimer')]
    public function supprimer($id): Response
        {
            // Récupérer le vehicule par son id
            $vehicule = $this->repository->find($id);
    
            if (!$vehicule) {
                throw $this->createNotFoundException('Aucun véhicule avec l\'identifiant ' . $id . ' n\'a été trouvé');
            }
            
            $equipementVehicule = $this->entity_manager->getRepository(EquipementVehicule::class)->findBy(['eqve_vehicule' => $vehicule]);
            
            foreach($equipementVehicule as $eq){
                $this->entity_manager->remove($eq);
            }
            
            // Suppression du vehicule
            $this->entity_manager->remove($vehicule);
            $this->entity_manager->flush();
    
            return $this->redirectToRoute('vehicule_lister');
        }

    /**
     * Supprimer tous les véhicules (debug/test)
     */
    #[Route('/vehicule/supprimer_tout', name: 'vehicule_supprimer_tout')]
    public function supprimer_tout(): Response
        {
            // Récupérer les vehicules
            $vehicules = $this->repository->findAll();
    
            foreach($vehicules as $vehicule) {
                $this->entity_manager->remove($vehicule);
                
                $equipementVehicules = $this->entity_manager->getRepository(EquipementVehicule::class)->findAll();
                
                foreach($equipementVehicules as $eq){
                    $this->entity_manager->remove($eq);
                }
                
                $this->entity_manager->remove($vehicule);
            }
            
            
            /*
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
             */
        
            $this->entity_manager->flush();
    
            return $this->redirectToRoute('vehicule_lister');
        }

    /**
     * Ajout d'un nouveau véhicule
     */
    #[Route('/vehicule/ajouter', name: 'vehicule_ajouter')]
    public function ajouter(Request $request): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
 
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->save($vehicule, true);
 
            return $this->redirectToRoute('vehicule_lister');
        }
 
        return $this->render('vehicule/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Modifier un véhicule étant donné son id
     */
    #[Route('/vehicule/modifier/{id}', name: 'vehicule_modifier')]
    public function modifier(Request $request, int $id): Response
        {
            $vehicule = $this->repository->find($id);
        
            if (!$vehicule) {
                throw $this->createNotFoundException('Aucun véhicule avec l\'identifiant ' . $id . ' n\'a été trouvé');
            }
    
            $form = $this->createForm(VehiculeType::class, $vehicule);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->repository->save($vehicule, true);

                return $this->redirectToRoute('vehicule_lister');
            }
    
            return $this->render('vehicule/modifier.html.twig', [
                'form' => $form->createView(),
            ]);
    
        }
}

?>







   
 
 
}
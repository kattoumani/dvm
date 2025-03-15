<?php
namespace App\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
 
// inclure la classe Conducteur
use App\Entity\Conducteur;

class ConducteurType extends AbstractType
{
    /**
     * Création d'un formulaire de saisie
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // le conducteur ne contient qu'un champ nom de type string
        $builder
            ->add('co_nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ]);
           
            // ajout d'un gestionnaire d'évènement afin de modifier le nom du conducteur
            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
   
                // Si le nom existe alors mettre la première lettre en majuscule
                // et les autres lettres en minuscules
                if (isset($data['co_nom'])) {
                    $data['co_nom'] = ucfirst(strtolower($data['co_nom']));
                }
   
                // modifier l'évènement avec les nouvelles données
                $event->setData($data);
            });
    }
 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Conducteur::class
        ]);
    }

        //...
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
 
}
?>
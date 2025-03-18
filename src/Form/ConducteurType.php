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
}
?>
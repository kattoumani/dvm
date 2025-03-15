<?php
namespace App\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use App\Entity\Equipement;
 
class EquipementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eq_libelle', TextType::class, [
                'label' => 'Libellé',
                'required' => true,
            ])->add('eq_prix', NumberType::class, [
                'label' => 'Prix',
                'required' => true,
            ]);
    }
 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Equipement::class
        ]);
    }
}
?>
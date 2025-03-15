<?php 
namespace App\Form;

use App\Entity\EquipementVehicule;
use App\Entity\Equipement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class EquipementVehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eqVeEquipement', EquipementType::class) // formulaire imbriqué pour l'équipement
            ->add('eqve_quantite', IntegerType::class, [
                'label' => 'Quantité',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EquipementVehicule::class,
        ]);
    }
}

?>
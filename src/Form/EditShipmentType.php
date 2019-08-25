<?php


namespace App\Form;


use App\Entity\Shipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditShipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('price', NumberType::class)
            ->add('Edit', SubmitType::class);

//        $builder->add(
//            'submit',
//            SubmitType::class,
//            [
//                'label' => 'Edit',
//                'attr' => [
//                    'icon' => 'fa fa-cart-plus'
//                ]
//            ]
//        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Shipment::class
        ]);
    }
}
<?php


namespace App\Form;

use App\Entity\CartOrder;
use App\Entity\Shipment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SetShipmentType extends AbstractType
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction($this->urlGenerator->generate('cart.setShipment'));

        $builder->add(
            'id',
            HiddenType::class
        );

        $builder->add(
            'shipment',
            EntityType::class,
            [
                'class' => Shipment::class,
                'choice_label' => function (Shipment $shipment) {
                    return "{$shipment->getName()} ({$shipment->getPrice()})";
                },
                'placeholder' => 'Select Shipment',
                'empty_data' => null
            ]
        );

        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'Shipment'
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CartOrder::class,
        ));
    }
}
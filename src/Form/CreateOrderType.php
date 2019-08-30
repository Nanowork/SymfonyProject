<?php


namespace App\Form;

use App\Entity\CartOrder;
use App\Entity\Payment;
use App\Entity\Shipment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateOrderType extends AbstractType
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction($this->urlGenerator->generate('order.createOrder'));
        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'Checkouttttt',
                'attr' => [
                    'icon' => 'fa fa-cart-plus'
                ]
            ]
        );

        $builder->add(
            'payment',
            EntityType::class,
            [
                'class' => Payment::class,
                'choice_label' => function (Payment $payment) {
                    return "{$payment->getName()}";
                },
                'placeholder' => 'Choose payment'
            ]
        );

        $builder->add(
            'shipment',
            EntityType::class,
            [
                'class' => Shipment::class,
                'choice_label' => function (Shipment $shipment) {
                    return "{$shipment->getName()} ({$shipment->getPrice()} $)";
                },
                'placeholder' => 'Choose shipment'
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
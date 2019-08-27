<?php


namespace App\Form;


use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProductType extends  AbstractType
{
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('image', TextType::class)
            ->add('price', NumberType::class);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, Category $category = null)
    {
        // 4. Add the province element
        $form->add('category', EntityType::class, array(
            'required' => true,
            'data' => $category,
            'placeholder' => 'Select a Category..',
            'class' => 'App\Entity\Category'
        ))
            ->add('Create', SubmitType::class);
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        $category = $this->em->getRepository('App\Entity\Category')->find($data['category']);

        $this->addElements($form, $category);
    }

    function onPreSetData(FormEvent $event) {
        $product = $event->getData();
        $form = $event->getForm();

        // When you create a new person, the City is always empty
        $category = $product->getCategory() ? $product->getCategory() : null;

        $this->addElements($form, $category);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
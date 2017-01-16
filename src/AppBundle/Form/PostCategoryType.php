<?php

namespace AppBundle\Form;

use AppBundle\Entity\PostCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextType::class, [
                'required' => true,
                'label' => 'Category name',
                'attr' => ['class' => 'test col-xs-6'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PostCategory::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'appbundle_post_category';
    }
}

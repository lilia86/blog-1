<?php

namespace AppBundle\Form;

use AppBundle\Entity\PostTag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostTagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Tag name',
                'attr' => ['class' => 'test col-xs-6'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PostTag::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'appbundle_post_tag';
    }
}

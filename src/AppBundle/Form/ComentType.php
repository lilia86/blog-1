<?php

namespace AppBundle\Form;

use AppBundle\Entity\Coment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('post', EntityType::class, array(
                'class' => 'AppBundle:Post',
                'label' => 'Post',
                'choice_label' => 'title',
                'disabled' => true
            ))
            ->add('content', TextareaType::class,[
                'required' => true,
                'label' => 'You coment'
            ])
        ;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $coment = $event->getData();
            $form = $event->getForm();
            if (!$coment || $coment->getId() === null) {
                $form->remove('post');
            }
        });
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Coment::class,

        ));
    }


    public function getBlockPrefix()
    {
        return 'appbundle_coment';
    }


}

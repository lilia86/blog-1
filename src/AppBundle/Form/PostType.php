<?php

namespace AppBundle\Form;

use AppBundle\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PostType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, array(
                'class' => 'AppBundle:UserBloger',
                'label' => 'Author',
                'choice_label' => 'nickName',
                'disabled' => true
            ))
            ->add('title', TextType::class,[
                'required' => true,
                'label' => 'Title',
                'attr' => ['class' => 'test col-xs-6']
            ])
            ->add('body', TextareaType::class,[
                'required' => true,
                'label' => 'Body',
                'attr' => ['class' => 'test col-xs-6']
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Image',
                'attr' => ['class' => 'test col-xs-6']
            ])
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle:PostCategory',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.description', 'ASC');
                },
                'choice_label' => 'description'

            ))
            ->add('tags', EntityType::class, array(
                'class' => 'AppBundle:PostTag',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,

            ))
            ->add('created_at', DateTimeType::class, array(
                'disabled' => true
            ))
            ->add('updated_at', DateTimeType::class, array(
                'disabled' => true
            ))
        ;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $post = $event->getData();
            $form = $event->getForm();
            if (!$post || $post->getId() === null) {
                $form->remove('user');
                $form->remove('created_at');
                $form->remove('updated_at');
            }
        });
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Post::class
        ));

    }


    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }


}

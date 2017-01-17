<?php

namespace AppBundle\Form;

use AppBundle\Entity\UserBloger;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'User Name',

            ])
            ->add('firstName', TextType::class, [
                'required' => false,
                'label' => 'First Name',

            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'Last Name',

            ])
            ->add('password', RepeatedType::class, [
                   'type' => PasswordType::class,
                   'required' => true,
                   'first_options' => ['label' => 'Password*'],
                   'second_options' => ['label' => 'Repeat Password*'],
                           ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => ['class' => 'test col-xs-6'],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();
            if ($user->getId()) {
                $form->remove('username');
            }
            if ($user instanceof UserBloger) {
                $form->add('information', UserDataType::class);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,

        ));
    }

    public function getBlockPrefix()
    {
        return 'appbundle_bloger';
    }
}

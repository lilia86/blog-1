<?php

namespace AppBundle\Form;


use AppBundle\Entity\UserBloger;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\User;

class UserBlogerType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickName', TextType::class,[
                'required' => true,
                'label' => 'NickName',

            ])
            ->add('firstName', TextType::class,[
                'required' => false,
                'label' => 'Name',

            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'lastName',

            ])
            ->add('password', TextType::class,[
                'required' => true,
                'label' => 'password',

            ])
            ->add('information', UserDataType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();
            if ($user->getId()) {
                $form->remove('nickName');
            }
        });

    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserBloger::class,

        ));
    }


    public function getBlockPrefix()
    {
        return 'appbundle_bloger';
    }


}

<?php

namespace AppBundle\Form;


use AppBundle\Entity\UserData;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserDataType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'required' => true,
                'label' => 'Email',
                'attr' => ['class' => 'test col-xs-6']
            ])
            ->add('address', TextType::class,[
                'required' => false,
                'label' => 'Address',
                'attr' => ['class' => 'test col-xs-6']
            ])
            ;

    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserData::class,
           /* 'em' => null*/
        ));
      /*  $resolver->addAllowedTypes('em', [ObjectManager::class]);*/
    }


    public function getBlockPrefix()
    {
        return 'appbundle_user_data';
    }


}

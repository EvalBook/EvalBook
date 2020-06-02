<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserConfiguration;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('showLogo')
        ;

        if(in_array('ROLE_ADMIN', $options['roles'])) {
            $builder
                ->add('isGlobalConfig')
            ;
        }

        $builder
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserConfiguration::class,
            'roles' => ['ROLE_USER']
        ]);
    }
}

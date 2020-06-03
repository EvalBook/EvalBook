<?php

namespace App\Form;

use App\Entity\UserConfiguration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('showLogo', ChoiceType::class, [
                'expanded' => false,
                'required' => true,
                'choices' => $options['choices'],
            ])

            ->add('showFooter', ChoiceType::class, [
                'expanded' => false,
                'required' => true,
                'choices' => $options['choices'],
            ])

            ->add('showHelp', ChoiceType::class, [
                'expanded' => false,
                'required' => true,
                'choices' => $options['choices'],
            ])

            ->add('showTitle', ChoiceType::class, [
                'expanded' => false,
                'required' => true,
                'choices' => $options['choices'],
            ])

            ->add('showSearch', ChoiceType::class, [
                'expanded' => false,
                'required' => true,
                'choices' => $options['choices'],
            ])
        ;

        if(in_array('ROLE_ADMIN', $options['roles'])) {
            $builder
                ->add('isGlobalConfig', ChoiceType::class, [
                    'expanded' => false,
                    'required' => true,
                    'choices' => $options['choices'],
                ])
            ;
        }

        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserConfiguration::class,
            'roles' => ['ROLE_USER'],
            'choices' => [
                'Yes' => true,
                'No'  => false,
            ],
            'translation_domain' => 'templates',
        ]);
    }
}

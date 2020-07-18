<?php

/**
 * Copyleft (c) 2020 EvalBook
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the European Union Public Licence (EUPL V 1.2),
 * version 1.2 (or any later version).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * European Union Public Licence for more details.
 *
 * You should have received a copy of the European Union Public Licence
 * along with this program. If not, see
 * https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12
 **/

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
            ->add('showLogo', ChoiceType::class, ['choices' => $options['choices']])
            ->add('showHelp', ChoiceType::class, ['choices' => $options['choices']])
            ->add('showTitle', ChoiceType::class, ['choices' => $options['choices']])
            ->add('showSearch', ChoiceType::class, ['choices' => $options['choices']])
            ->add('useSchools', ChoiceType::class, ['choices' => $options['choices']])
            ->add('useContacts', ChoiceType::class, ['choices' => $options['choices']])
        ;

        if(in_array('ROLE_ADMIN', $options['roles'])) {
            $builder
                ->add('isGlobalConfig', ChoiceType::class, [
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
            'expanded' => false,
            'required' => true,
            'choices' => [
                'Yes' => true,
                'No'  => false,
            ],
            'translation_domain' => 'templates',
        ]);
    }
}

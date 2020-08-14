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

use App\Entity\ActivityThemeDomain;
use App\Entity\KnowledgeType;
use App\Entity\Period;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ActivityType extends AbstractType
{

    /**
     * Build a form to add / edit activities.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Form fields.
        $builder

            ->add('activityThemeDomains', EntityType::class, [
                // Used to filter knowledge types with JS.
                'class' => ActivityThemeDomain::class,
                'choices' => $options['activity_theme_domains'],
                'mapped' => false,
                'required' => true,
                'group_by' => 'activityTheme',
                'placeholder' => 'Choose an activity domain to continue...',
                'translation_domain' => 'templates',
            ])

            ->add('knowledgeType', EntityType::class, [
                'class' => KnowledgeType::class,
                'required' => true,

            ])

            // Available periods.
            ->add('period', EntityType::class, [
                'class' => Period::class,
                'choices' => $options['periods'],
                'required' => true,
            ])

            // The note type.
            ->add('noteType', EntityType::class, [
                'class' => \App\Entity\NoteType::class,
                'placeholder' => "Select an available note type...",
                'translation_domain' => 'templates',
            ])

            // Activity name.
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'activity.name-empty',
                    ])
                ],
                'required' => true,
            ])

            ->add('comment', HiddenType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * Default options.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Activity::class,
            'periods' => array(),
            'activity_theme_domains' => array(),
        ]);
    }
}
